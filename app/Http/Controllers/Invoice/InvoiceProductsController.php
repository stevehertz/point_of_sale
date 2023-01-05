<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\InvoiceProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvoiceProductsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'organization_id' => 'required|integer|exists:organizations,id',
            'invoice_id' => 'required|integer|exists:invoices,id',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer',
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

        $current_product = Product::findOrFail($data['product_id']);

        $product = new InvoiceProduct();

        $product->user_id = Auth::user()->id;
        $product->organization_id = $data['organization_id'];
        $product->invoice_id = $data['invoice_id'];
        $product->product_id = $current_product->id;
        $product->quantity = $data['quantity'];
        $product->price = $current_product->selling_price;
        $total = $current_product->selling_price * $product->quantity;
        $product->total = $total;

        if($product->save()){
            $response['status'] = true;
            $response['message'] = 'Product added successfully';
            return response()->json($response);
        }else{
            $response['status'] = false;
            $response['error'] = 'Something went wrong';
            return response()->json($response);
        }

    }

    public function destroy(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'invoice_product_id' => 'required|integer|exists:invoice_products,id',
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

        $invoice_product = InvoiceProduct::findOrFail($data['invoice_product_id']);

        if($invoice_product->delete()){
            $response['status'] = true;
            $response['message'] = 'Product deleted successfully';
            return response()->json($response);
        }else{
            $response['status'] = false;
            $response['error'] = 'Something went wrong';
            return response()->json($response);
        }
    }
}
