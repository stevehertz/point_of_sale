<?php

namespace App\Http\Controllers\Receipts;

use App\Http\Controllers\Controller;
use App\Http\Resources\Receipt as ResourcesReceipt;
use App\Models\Customer;
use App\Models\Organization;
use App\Models\Receipt;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ReceiptsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $id)
    {
        # code...
        $organization = Organization::find($id);
        if ($request->ajax()) {
            $data = Receipt::where('organization_id', $organization->id)
                ->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="viewReceiptBtn btn btn-warning btn-sm" data-id="' . $row->id . '"><i class="fa fa-file"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $page_title = 'Receipts';
        return view('back.receipts.index', compact('page_title', 'organization'));
    }

    public function show(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'receipt_id' => 'required|integer|exists:receipts,id',
        ]);

        if ($validator->fails()) {
            $response['status'] = false;
            $response['error'] = $validator->errors()->first();
            return response()->json($response, 422);
        }

        $receipt = Receipt::find($data['receipt_id']);
        $request->session()->put('receipt_id', $receipt->id);
        return new ResourcesReceipt($receipt);
    }

    public function view(Request $request, $id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        if ($request->session()->has('receipt_id')) {
            $receipt_id = $request->session()->get('receipt_id');
            $receipt = Receipt::findOrFail($receipt_id);
            $request->session()->forget('receipt_id');
            $customer = $receipt->customer;
            $sale = $receipt->sale;
            $sale_products = $sale->sale_product;
            $payment_method = $receipt->payment_method;
            $page_title = 'Receipt #' . $receipt->id;
            return view('back.receipts.view', [
                'organization' => $organization,
                'receipt' => $receipt,
                'customer' => $customer,
                'payment_method' => $payment_method,
                'sale_products' => $sale_products,
                'page_title' => $page_title,
            ]);
        }
        return redirect()->route('back.receipts.index', $organization->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * Create receipt on a sale
     * @param sale_id
     */
    public function store(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'sale_id' => 'required|integer|exists:sales,id',
        ]);

        if($validator->fails()){
            $response['status'] = false;
            $output = '<ul>';
            foreach ($validator->errors()->all() as $error) {
                $output .= '<li>' . $error . '</li>';
            }
            $output .= '</ul>';
            $response['error'] = $output;
            return response()->json($response);
        }

        $sale = Sale::find($data['sale_id']);

        $receipt = new Receipt();

        $receipt->organization_id = $sale->organization_id;
        $receipt->sale_id = $sale->id;
        $receipt->type = 'SALES RECEIPT';
        if($sale->customer_id == NULL){
            $receipt->customer_id = NULL;
            $receipt->customer_name = 'Walk-in';
        }else{
            $receipt->customer_id = $sale->customer_id;
            $receipt->customer_name = $sale->customer_name;
        }
        $receipt->payment_method_id = $sale->payment_method_id;
        $receipt->payment_date = $sale->sales_date;
        $receipt->total_amount = $sale->total;
        $receipt->discount = $sale->discount;
        $receipt->tax = $sale->tax;
        $receipt->sale_tax = $sale->sale_tax;
        $receipt->shipping = $sale->shipping;
        $receipt->paid_amount = $sale->paid;
        $receipt->change = $sale->change;
        $receipt->served_by = Auth::user()->first_name . ' ' . Auth::user()->last_name;

        if($receipt->save()){
            $response['status'] = true;
            $response['message'] = 'Receipt created successfully';
            $response['receipt_id'] = $receipt->id;
            return response()->json($response);
        }else{
            $response['status'] = false;
            $response['error'] = 'Something went wrong';
            return response()->json($response);
        }

    }

    public function print(Request $request, $id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        if ($request->session()->has('receipt_id')) {
            $receipt_id = $request->session()->get('receipt_id');
            $receipt = Receipt::findOrFail($receipt_id);
            $customer = $receipt->customer;
            $sale = $receipt->sale;
            $sale_products = $sale->sale_product;
            $payment_method = $receipt->payment_method;
            $request->session()->forget('receipt_id');
            $page_title = 'Print Receipt #' . $receipt->id;
            return view('back.receipts.print', [
                'organization' => $organization,
                'receipt' => $receipt,
                'customer' => $customer,
                'payment_method' => $payment_method,
                'sale_products' => $sale_products,
                'page_title' => $page_title,
            ]);
        }
        return redirect()->route('back.receipts.index', $organization->id);
    }
}
