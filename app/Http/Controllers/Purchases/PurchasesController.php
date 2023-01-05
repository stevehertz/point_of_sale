<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Http\Resources\Purchase as ResourcesPurchase;
use App\Models\Organization;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Supplier;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade as PDF;

class PurchasesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request, $id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        if ($request->ajax()) {
            $data = Purchase::where('organization_id', $organization->id)->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="' . $row['id'] . '" class="btn btn-warning btn-sm viewBtn"><i class="fa fa-file"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $page_title = 'New Purchase';
        return view('back.purchases.index', [
            'page_title' => $page_title,
            'organization' => $organization
        ]);
    }

    public function create($id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        $page_title = 'New Purchase';
        $suppliers = $organization->supplier->sortBy('created_at', SORT_DESC);
        return view('back.purchases.create', [
            'page_title' => $page_title,
            'organization' => $organization,
            'suppliers' => $suppliers
        ]);
    }

    public function store(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'organization_id' => 'required|integer|exists:organizations,id',
            'supplier_id' => 'required|integer|exists:suppliers,id',
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

        $supplier = Supplier::findOrFail($data['supplier_id']);

        $purchase = new Purchase();

        $purchase->organization_id = $organization->id;
        $purchase->supplier_id = $supplier->id;
        $purchase->supplier_name = $supplier->full_names;
        $purchase->purchase_date = $data['purchase_date'];
        $purchase->order_amount = 0;
        $purchase->discount = 0;
        $purchase->subtotal = 0;
        $purchase->prev_balance = $supplier->balance;
        $purchase->total_amount = 0;
        $purchase->paid_amount = 0;
        $purchase->balance = 0;
        $purchase->purchase_status = 0;
        $purchase->order_status = 0;
        $purchase->payment_status = 0;

        if ($purchase->save()) {
            $request->session()->put('purchase_id', $purchase->id);
            $response['status'] = true;
            $response['message'] = 'Purchase has been added successfully.';
        } else {
            $response['status'] = false;
            $response['error'] = 'Something went wrong, please try again.';
        }

        return response()->json($response);
    }

    public function show(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'purchase_id' => 'required|integer|exists:purchases,id',
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

        $purchase = Purchase::findOrFail($data['purchase_id']);

        $request->session()->put('purchase_id', $purchase->id);

        return new ResourcesPurchase($purchase);
    }

    public function view(Request $request, $id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        if ($request->session()->has('purchase_id')) {
            $purchase = Purchase::findOrFail($request->session()->get('purchase_id'));
            $request->session()->forget('purchase_id');
            $page_title = 'Purchase #' . $purchase->id;
            $supplier = $purchase->supplier;
            $purchase_products = $purchase->purchase_product;
            return view('back.purchases.view', [
                'page_title' => $page_title,
                'organization' => $organization,
                'purchase' => $purchase,
                'supplier' => $supplier,
                'purchase_products' => $purchase_products
            ]);
        }

        return redirect()->route('back.purchases.index', $organization->id);
    }

    public function pdf(Request $request, $id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        if ($request->session()->has('purchase_id')) {
            $purchase = Purchase::findOrFail($request->session()->get('purchase_id'));
            $request->session()->forget('purchase_id');
            $page_title = 'Purchase #' . $purchase->id;
            $supplier = $purchase->supplier;
            $purchase_products = $purchase->purchase_product;

            $data = [
                'organization' => $organization,
                'page_title' => $page_title,
                'purchase' => $purchase,
                'supplier' => $supplier,
                'purchase_products' => $purchase_products,
            ];

            $pdf = PDF::loadView('back.purchases.pdf', $data);

            return $pdf->download('purchase_#' . date('d-m-Y-H-i') . $purchase->id . '.pdf');
        }
        return redirect()->route('back.purchases.index', $organization->id);
    }

    public function send(Request $request, $id)
    {
        # code...
        $organization = Organization::findOrFail($id);

        $data = [
            'organization' => $organization,
        ];

        $pdf = PDF::loadView('back.pdf.purchase', $data);

        return $pdf->download('purchase.pdf');
    }


    public function print(Request $request, $id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        if ($request->session()->has('purchase_id')) {
            $purchase = Purchase::findOrFail($request->session()->get('purchase_id'));
            $request->session()->forget('purchase_id');
            $page_title = 'Print Purchase #' . $purchase->id;
            $supplier = $purchase->supplier;
            $purchase_products = $purchase->purchase_product;
            return view('back.purchases.print', [
                'page_title' => $page_title,
                'organization' => $organization,
                'purchase' => $purchase,
                'supplier' => $supplier,
                'purchase_products' => $purchase_products
            ]);
        }
        return redirect()->route('back.purchases.index', $organization->id);
    }

    public function edit(Request $request, $id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        if ($request->session()->has('purchase_id')) {
            $purchase = Purchase::findOrFail($request->session()->get('purchase_id'));
            $request->session()->forget('purchase_id');
            $page_title = 'Edit Purchase #' . $purchase->id;
            $supplier = $purchase->supplier;
            $purchase_products = $purchase->purchase_product;
            $products = $organization->product;
            $suppliers = $organization->supplier;
            return view('back.purchases.edit', [
                'page_title' => $page_title,
                'organization' => $organization,
                'purchase' => $purchase,
                'supplier' => $supplier,
                'products' => $products,
                'purchase_products' => $purchase_products,
                'suppliers' => $suppliers
            ]);
        }
        return redirect()->route('back.purchases.index', $organization->id);
    }


    public function update_total(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'purchase_id' => 'required|integer|exists:purchases,id',
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

        $purchase = Purchase::findOrFail($data['purchase_id']);

        $purchase_products = PurchaseProduct::where('user_id', Auth::user()->id)
            ->where('organization_id', $purchase->organization_id)
            ->where('purchase_id', $purchase->id)
            ->sum('total_amount');
        $purchase->id = $purchase->id;

        $purchase->order_amount = $purchase_products;
        $purchase->discount = $purchase->discount;
        $subtotal = $purchase->order_amount - $purchase->discount;
        $purchase->subtotal = $subtotal;
        $supplier = Supplier::findOrFail($purchase->supplier_id);
        $purchase->prev_balance = $supplier->balance;
        $total_amount = $purchase->subtotal + $purchase->prev_balance;
        $purchase->total_amount = $total_amount;
        $purchase->paid_amount = 0;
        $balance = $purchase->total_amount - $purchase->paid_amount;
        $purchase->balance = $balance;

        if ($purchase->save()) {
            $response['status'] = true;
            $response['message'] = 'Purchase has been updated successfully.';
        } else {
            $response['status'] = false;
            $response['error'] = 'Something went wrong, please try again.';
        }

        return response()->json($response);
    }

    public function update_payments(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'purchase_id' => 'required|integer|exists:purchases,id',
            'discount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
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

        $purchase = Purchase::findOrFail($data['purchase_id']);

        $purchase->id = $purchase->id;

        $purchase->discount = $data['discount'];
        $subtotal = $purchase->order_amount - $purchase->discount;
        $purchase->subtotal = $subtotal;
        $total = $purchase->subtotal + $purchase->prev_balance;
        $purchase->total_amount = $total;
        $purchase->paid_amount = $data['paid_amount'];
        $balance = $purchase->total_amount - $purchase->paid_amount;
        $purchase->balance = $balance;
        $purchase->order_status = 1;

        $purchase->save();

        $supplier = Supplier::findOrFail($purchase->supplier_id);

        $supplier->id = $supplier->id;
        $supplier->balance = $purchase->balance;

        $supplier->save();

        $response['status'] = true;
        $response['message'] = 'Purchase has been updated successfully.';

        return response()->json($response);
    }
}
