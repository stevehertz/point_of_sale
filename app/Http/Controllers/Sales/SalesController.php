<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Resources\Sale as ResourcesSale;
use App\Models\Customer;
use App\Models\Organization;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\Sale;
use App\Models\SaleProduct;
use Barryvdh\DomPDF\PDF;
// use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SalesController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        //
        $organization = Organization::findOrFail($id);
        if ($request->ajax()) {
            $data = Sale::where('user_id', Auth::user()->id)
                ->where('organization_id', $organization->id)
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="' . $row['id'] . '" class="btn btn-warning btn-sm viewBtn"><i class="fa fa-file"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $page_title  = "Sales Lists";
        $request->session()->forget('sales_id');
        return view('back.sales.index', [
            'page_title' => $page_title,
            'organization' => $organization,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
        $organization = Organization::findOrFail($id);
        $customers = Customer::where('user_id', Auth::user()->id)
            ->where('organization_id', $organization->id)
            ->latest()->get();
        $products = $organization->product;
        $page_title = "New Sale";
        return view('back.sales.create', [
            'page_title' => $page_title,
            'organization' => $organization,
            'customers' => $customers,
            'products' => $products,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->all();

        $validator = Validator::make($data, [
            'organization_id' => 'required|integer|exists:organizations,id',
            'customer_id' => 'required|integer',
            'sales_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            $response['status'] = false;
            $output = '<ul>';
            foreach ($validator->errors()->all() as $error) {
                $output .= '<li>' . $error . '</li>';
            }
            $output .= '</ul>';
            $response['error'] = $output;
            return response()->json($response);
        }

        $organization = Organization::findOrFail($data['organization_id']);

        $sale = new Sale();

        $sale->user_id = Auth::user()->id;
        $sale->organization_id = $organization->id;
        if ($data['customer_id'] == 0) {
            $sale->customer_id = NULL;
            $sale->customer_name = 'Walk-in';
        } else {
            $customer = Customer::findOrFail($data['customer_id']);
            $sale->customer_id = $customer->id;
            $sale->customer_name = $customer->full_names;
        }
        $sale->payment_method_id = 0;
        $sale->sales_date = $data['sales_date'];
        $sale->order_total = number_format(0, 2, '.', ',');
        $sale->discount = number_format(0, 2, '.', ',');
        $sale->subtotal = number_format(0, 2, '.', ',');
        $sale->prev_balance = number_format(0, 2, '.', ',');
        $sale->total = number_format(0, 2, '.', ',');
        $sale->paid = number_format(0, 2, '.', ',');
        $sale->balance = number_format(0, 2, '.', ',');

        if ($sale->save()) {
            $response['status'] = true;
            $request->session()->put('sales_id', $sale->id);
            $response['sale_id'] = $sale->id;
            $response['message'] = 'Sale created successfully';

            return response()->json($response);
        } else {
            $response['status'] = false;
            $response['error'] = "Failed to save data";
            return response()->json($response);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
        $data = $request->all();

        $validator = Validator::make($data, [
            'sales_id' => 'required|integer|exists:sales,id',
        ]);

        if ($validator->fails()) {
            $response['status'] = false;
            $output = '<ul>';
            foreach ($validator->errors()->all() as $error) {
                $output .= '<li>' . $error . '</li>';
            }
            $output .= '</ul>';
            $response['error'] = $output;
            return response()->json($response);
        }

        $sale = Sale::findOrFail($data['sales_id']);
        $request->session()->put('sales_id', $sale->id);
        return new ResourcesSale($sale);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request, $id)
    {
        //
        $organization = Organization::findOrFail($id);
        if ($request->session()->has('sales_id')) {
            $sale = Sale::findOrFail($request->session()->get('sales_id'));
            if ($sale->customer_id == NULL) {
                $customer = 'walk-in';
            } else {
                $customer = $sale->customer;
            }
            $products = Product::where('user_id', Auth::user()->id)
                ->where('organization_id', $organization->id)
                ->where('stocks', '>', 0)
                ->latest()->get();
            $sale_products = SaleProduct::where('user_id', Auth::user()->id)
                ->where('organization_id', $organization->id)
                ->where('sale_id', $sale->id)
                ->latest()->get();
            $payment_methods = PaymentMethod::where('user_id', Auth::user()->id)
                ->where('organization_id', $organization->id)
                ->latest()->get();
            return view('back.sales.view', [
                'page_title' => "Sale #" . $sale->id,
                'organization' => $organization,
                'sale' => $sale,
                'customer' => $customer,
                'products' => $products,
                'sale_products' => $sale_products,
                'payment_methods' => $payment_methods,
            ]);
        }
        return redirect()->route('back.sales.index', $organization->id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
        $organization = Organization::findOrFail($id);
        if ($request->session()->has('sales_id')) {
            $sale = Sale::findOrFail($request->session()->get('sales_id'));
            if ($sale->customer_id == NULL) {
                $customer = 'walk-in';
            } else {
                $customer = $sale->customer;
            }
            $page_title = "Edit Sale #" . $sale->id;
            $products = $organization->product;
            $sale_products = $sale->sale_product;
            $payment_methods = $organization->payment_method;
            return view('back.sales.edit', [
                'page_title' => $page_title,
                'organization' => $organization,
                'sale' => $sale,
                'customer' => $customer,
                'products' => $products,
                'sale_products' => $sale_products,
                'payment_methods' => $payment_methods,
            ]);
        }
        return redirect()->route('back.sales.index', $organization->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_discount(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'sale_id' => 'required|integer|exists:sales,id',
            'discount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            $response['status'] = false;
            $output = '<ul>';
            foreach ($validator->errors()->all() as $error) {
                $output .= '<li>' . $error . '</li>';
            }
            $output .= '</ul>';
            $response['error'] = $output;
            return response()->json($response);
        }

        $sale = Sale::findOrFail($data['sale_id']);

        $sale->id = $sale->id;

        $sale->order_total = $sale->order_total;
        $sale->discount = $data['discount'];
        $discounted_total = $sale->order_total - $sale->discount;
        $sale->tax = $sale->tax;
        $sale->sale_tax = $sale->sale_tax;
        $sale->shipping = $sale->shipping;
        $subtotal = $discounted_total + $sale->sale_tax + $sale->shipping;
        $sale->subtotal = $subtotal;
        $sale->prev_balance = $sale->prev_balance;
        $sale->total = $sale->subtotal + $sale->prev_balance;
        $sale->paid = $sale->paid;
        $sale->balance = $sale->total - $sale->paid;

        $sale->save();

        $response['status'] = true;
        $response['message'] = 'Sale has been updated successfully.';

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_tax(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'sale_id' => 'required|integer|exists:sales,id',
            'tax' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            $response['status'] = false;
            $output = '<ul>';
            foreach ($validator->errors()->all() as $error) {
                $output .= '<li>' . $error . '</li>';
            }
            $output .= '</ul>';
            $response['error'] = $output;
            return response()->json($response);
        }

        $sale = Sale::findOrFail($data['sale_id']);

        $tax = $data['tax'];
        $sale_tax = ($sale->order_total * $tax) / 100;

        $sale->id = $sale->id;

        $sale->order_total = $sale->order_total;
        $sale->discount = $sale->discount;
        $sale->tax = $tax;
        $sale->sale_tax = $sale_tax;
        $discounted_total = $sale->order_total - $sale->discount;
        $sale->shipping = $sale->shipping;
        $subtotal = $discounted_total + $sale_tax + $sale->shipping;
        $sale->subtotal = $subtotal;
        $sale->prev_balance = $sale->prev_balance;
        $sale->total = $sale->subtotal + $sale->prev_balance;
        $sale->paid = $sale->paid;
        $sale->balance = $sale->total - $sale->paid;

        $sale->save();

        $response['status'] = true;
        $response['message'] = 'Sale has been updated successfully.';

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_shipping(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'sale_id' => 'required|integer|exists:sales,id',
            'shipping' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            $response['status'] = false;
            $output = '<ul>';
            foreach ($validator->errors()->all() as $error) {
                $output .= '<li>' . $error . '</li>';
            }
            $output .= '</ul>';
            $response['error'] = $output;
            return response()->json($response);
        }

        $sale = Sale::findOrFail($data['sale_id']);

        $shipping = $data['shipping'];

        $sale->id = $sale->id;

        $sale->order_total = $sale->order_total;
        $sale->discount = $sale->discount;
        $sale->tax = $sale->tax;
        $sale->sale_tax = $sale->sale_tax;
        $sale->shipping = $shipping;
        $discounted_total = $sale->order_total - $sale->discount;
        $subtotal = $discounted_total + $sale->sale_tax + $sale->shipping;
        $sale->subtotal = $subtotal;
        $sale->prev_balance = $sale->prev_balance;
        $sale->total = $sale->subtotal + $sale->prev_balance;
        $sale->paid = $sale->paid;
        $sale->balance = $sale->total - $sale->paid;

        $sale->save();

        $response['status'] = true;
        $response['message'] = 'Sale has been updated successfully.';

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_payments(Request $request)
    {

        $data = $request->all();

        $validator = Validator::make($data, [
            'sale_id' => 'required|integer|exists:sales,id',
            'payment_method_id' => 'required|integer|exists:payment_methods,id',
            'paid' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            $response['status'] = false;
            $output = '<ul>';
            foreach ($validator->errors()->all() as $error) {
                $output .= '<li>' . $error . '</li>';
            }
            $output .= '</ul>';
            $response['error'] = $output;
            return response()->json($response);
        }

        // 1. update sale
        $sale = Sale::findOrFail($data['sale_id']);

        $sale->id = $sale->id;
        $sale->payment_method_id = $data['payment_method_id'];
        $sale->total = $sale->total;
        $sale->paid = $data['paid'];

        // check if amount paid is enough
        if ($sale->total > $sale->paid) {
            $response['status'] = false;
            $response['error'] = 'Payment is not enough.';
            return response()->json($response);
        }
        $sale->balance = $sale->total - $sale->paid;

        // process change
        $change = 0;
        if ($sale->balance < 0) {
            $change += abs($sale->balance);
            $sale->change = $change;
        } else {
            $change += 0;
            $sale->change = $change;
        }
        $sale->payment_status = 1;
        $sale->sale_status = 1;

        if ($sale->save()) {
            // update customer balance
            if ($sale->customer_id != NULL) {
                $customer = Customer::findOrFail($sale->customer_id);
                $customer->id = $customer->id;
                $customer->balance = $customer->balance + $sale->balance;
                if ($customer->save()) {
                    $response['status'] = true;
                    $response['message'] = 'Payments updated successfully';
                    return response()->json($response);
                } else {
                    $response['status'] = false;
                    $response['error'] = 'Something went wrong trying to update customer balance.';

                    return response()->json($response);
                }
            } else {
                $response['status'] = true;
                $response['message'] = 'Payments updated successfully';
                return response()->json($response);
            }
        } else {
            $response['status'] = false;
            $response['error'] = 'Something went wrong trying to update sales payments.';

            return response()->json($response);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function update_total(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'sale_id' => 'required|integer|exists:sales,id'
        ]);

        if ($validator->fails()) {
            $response['status'] = false;
            $output = '<ul>';
            foreach ($validator->errors()->all() as $error) {
                $output .= '<li>' . $error . '</li>';
            }
            $output .= '</ul>';
            $response['error'] = $output;
            return response()->json($response);
        }

        $sale = Sale::findOrFail($data['sale_id']);

        $sale_products = SaleProduct::where('organization_id', $sale->organization_id)
            ->where('sale_id', $sale->id)
            ->sum('total_price');

        $sale->id = $sale->id;

        $sale->order_total = $sale_products;
        if ($sale->order_total > 0) {
            $sale->discount = $sale->discount;
            $discounted_total = $sale->order_total - $sale->discount;
            $sale->tax = $sale->tax;
            $sale->sale_tax = $sale->sale_tax;
            $sale->shipping = $sale->shipping;
            $subtotal = $discounted_total + $sale->sale_tax + $sale->shipping;
            $sale->subtotal = $subtotal;

        }else{
            $sale->discount = 0;
            $sale->tax = 0;
            $sale->sale_tax = 0;
            $sale->shipping = 0;
            $sale->subtotal = 0;
        }


        if ($sale->customer_id != NULL) {
            $customer = Customer::findOrFail($sale->customer_id);
            $sale->prev_balance = $customer->balance;
        } else {
            $sale->prev_balance = $sale->prev_balance;
        }
        $sale->total = $sale->subtotal + $sale->prev_balance;
        $sale->paid = $sale->paid;
        $sale->balance = $sale->total - $sale->paid;

        if ($sale->save()) {
            $response['status'] = true;
            $response['message'] = 'Sale has been updated successfully.';
        } else {
            $response['status'] = false;
            $response['error'] = 'Something went wrong, please try again.';
        }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function print(Request $request, $id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        if ($request->session()->has('sales_id')) {
            $sale = Sale::findOrFail($request->session()->get('sales_id'));
            $customer = $sale->customer;
            $sale_products = $sale->sale_product;
            $payment_method = $sale->payment_method;
            $page_title = 'Print Sale';
            return view('back.sales.print', [
                'page_title' => $page_title,
                'organization' => $organization,
                'sale' => $sale,
                'customer' => $customer,
                'sale_products' => $sale_products,
                'payment_method' => $payment_method,
            ]);
        }
        return redirect()->route('back.sales.index', $organization->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request, $id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        if ($request->session()->has('sales_id')) {
            $sale = Sale::findOrFail($request->session()->get('sales_id'));
            $customer = $sale->customer;
            $sale_products = $sale->sale_product;
            $payment_method = $sale->payment_method;
            $page_title = 'Print Sale';
            $pdf = PDF::loadView('back.sales.pdf', [
                'page_title' => $page_title,
                'organization' => $organization,
                'sale' => $sale,
                'customer' => $customer,
                'sale_products' => $sale_products,
                'payment_method' => $payment_method,
            ]);
            $sale_name = 'Sale-' . $sale->id . '-' . date('d-m-Y-H-i') . '.pdf';
            return $pdf->download($sale_name);
        }
        return redirect()->route('back.sales.index', $organization->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        # code...
        $data = $request->all();
        $validator = Validator::make($data, [
            'sale_id' => 'required|exists:sales,id',
            'organization_id' => 'required|exists:organizations,id',
        ]);

        if ($validator->fails()) {
            $response['status'] = false;
            $output = '<ul>';
            foreach ($validator->errors()->all() as $error) {
                $output .= '<li>' . $error . '</li>';
            }
            $output .= '</ul>';
            $response['error'] = $output;
            return response()->json($response);
        }

        $sale = Sale::findOrFail($data['sale_id']);
        $organization = Organization::findOrFail($data['organization_id']);
        $customer = $sale->customer;
        if ($customer->full_names == 'Walk-in') {
            $response['status'] = false;
            $response['error'] = 'Walk-in customer cannot be sent.';
            return response()->json($response);
        }
        $sale_products = $sale->sale_product;
        $payment_method = $sale->payment_method;
        $page_title = 'Print Sale';

        $data['email'] = $customer->email;
        $data['title'] = 'Sale #' . $sale->id;
        $data['content'] = 'Sale #' . $sale->id . ' has been sent to your email.';

        $pdf = PDF::loadView('back.sales.pdf', [
            'page_title' => $page_title,
            'organization' => $organization,
            'sale' => $sale,
            'customer' => $customer,
            'sale_products' => $sale_products,
            'payment_method' => $payment_method,
        ]);

        Mail::send('back.mail.sales', $data, function ($message) use ($data, $pdf) {
            $message->to($data['email'], $data['email'])
                ->subject($data['title'])
                ->attachData($pdf->output(), 'Sale-' . date('d-m-Y-H-i') . '.pdf');
        });

        $response['status'] = true;
        $response['message'] = 'Sale has been sent to your email.';
        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
