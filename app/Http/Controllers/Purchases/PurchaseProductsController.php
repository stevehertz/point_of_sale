<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseProduct;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseProductsController extends Controller
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
            'purchase_id' => 'required|integer|exists:purchases,id',
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

        $product = Product::findOrFail($data['product_id']);

        $purchase_product = new PurchaseProduct();

        $purchase_product->user_id = Auth::user()->id;
        $purchase_product->organization_id = $data['organization_id'];
        $purchase_product->purchase_id = $data['purchase_id'];
        $purchase_product->product_id = $product->id;
        $purchase_product->quantity = $data['quantity'];
        $total = $purchase_product->quantity * $product->purchase_price;
        $purchase_product->total_amount = $total;

        $purchase_product->save();

        $response['status'] = true;
        $response['message'] = 'Product successfully added';
        return response()->json($response);
    }

    public function destroy(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'purchase_product_id' => 'required|integer|exists:purchase_products,id',
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

        $purchase_product = PurchaseProduct::findOrFail($data['purchase_product_id']);

        $purchase_product->delete();

        $response['status'] = true;
        $response['message'] = 'Product successfully removed from purchase';
        return response()->json($response);
    }
}
