<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Resources\Invoice as ResourcesInvoice;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Organization;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class InvoicesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        if ($request->ajax()) {
            $data = Invoice::join('customers', 'customers.id', '=', 'invoices.customer_id')
                ->select('invoices.*', 'customers.full_names as full_names')
                ->where('invoices.user_id', Auth::user()->id)
                ->where('invoices.organization_id', $organization->id)
                ->orderBy('invoices.id', 'desc')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="View" class="view btn btn-warning btn-sm viewInvoiceBtn"><i class="fa fa-file"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $request->session()->forget('invoice_id');
        $page_title = 'Invoices';
        return view('back.invoice.index', compact('page_title', 'organization'));
    }


    public function create($id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        $customers = Customer::where('user_id', Auth::user()->id)
            ->where('organization_id', $organization->id)
            ->latest()->get();
        $page_title = 'Create Invoice';
        return view('back.invoice.create', compact('page_title', 'organization', 'customers'));
    }

    public function store(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'organization_id' => 'required|integer|exists:organizations,id',
            'customer_id' => 'required|integer|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
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

        $invoice = new Invoice();

        $invoice->user_id = Auth::user()->id;
        $invoice->organization_id = $data['organization_id'];
        $invoice->customer_id = $data['customer_id'];
        $invoice->invoice_date = $data['invoice_date'];
        $invoice->order_total = 0;
        $invoice->prev_balance = 0;
        $invoice->subtotal = 0;
        $invoice->discount = 0;
        $invoice->total = 0;
        $invoice->due_date = $data['due_date'];
        $invoice->invoice_number = 0;
        $invoice->invoice_status = 'NEW';

        if ($invoice->save()) {
            $request->session()->put('invoice_id', $invoice->id);
            $response['status'] = true;
            $response['message'] = 'Invoice created successfully';
            return response()->json($response);
        } else {
            $response['status'] = false;
            $response['error'] = 'Something went wrong';
            return response()->json($response);
        }
    }

    public function show(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'invoice_id' => 'required|integer|exists:invoices,id',
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

        $invoice = Invoice::findOrFail($data['invoice_id']);
        $request->session()->put('invoice_id', $invoice->id);
        return new ResourcesInvoice($invoice);
    }

    public function view(Request $request, $id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        if ($request->session()->has('invoice_id')) {
            $invoice = Invoice::findOrFail($request->session()->get('invoice_id'));
            $customer = $invoice->customer;
            $invoice_products = $invoice->invoiceProduct;
            $products = Product::where('user_id', Auth::user()->id)
                ->where('organization_id', $organization->id)
                ->latest()->get();
            $page_title = 'View Invoice';
            return view('back.invoice.view', compact('page_title', 'organization', 'invoice', 'customer', 'invoice_products', 'products'));
        }
        return redirect()->route('back.invoices.index', $organization->id);
    }

    public function print(Request $request, $id)
    {
        # code...
        $organization = Organization::findOrFail($id);
    }

    public function update_amount(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'invoice_id' => 'required|integer|exists:invoices,id',
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

        $invoice = Invoice::findOrFail($data['invoice_id']);

        $invoice_products = $invoice->invoiceProduct->sum('total');

        $customer = $invoice->customer;

        $invoice->id = $invoice->id;
        $invoice->order_total = $invoice_products;
        $invoice->discount = $invoice->discount;
        $subtotal = $invoice->order_total - $invoice->discount;
        $invoice->subtotal = $subtotal;
        $invoice->prev_balance = $customer->balance;
        $total = $invoice->subtotal + $invoice->prev_balance;
        $invoice->total = $total;

        if($invoice->save()){
            $response['status'] = true;
            $response['message'] = 'Invoice updated successfully';

        }else{
            $response['status'] = false;
            $response['error'] = 'Something went wrong';
        }

        return response()->json($response);
    }

    public function destroy(Request $request)
    {
        # code...
    }
}
