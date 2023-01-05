<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SaleProductsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function store(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'organization_id' => 'required|integer|exists:organizations,id',
            'sale_id' => 'required|integer|exists:sales,id',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required',
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

        $product = Product::findOrFail($data['product_id']);

        $sale_product = new SaleProduct();

        $sale_product->user_id = Auth::user()->id;
        $sale_product->organization_id = $data['organization_id'];
        $sale_product->sale_id = $sale->id;
        $sale_product->product_id = $product->id;
        $sale_product->sale_price = $product->selling_price;
        $sale_product->quantity = $data['quantity'];
        if($sale_product->quantity > $product->stocks){
            $response['status'] = false;
            $response['error'] = 'Product quantity is greater than available stock';
            return response()->json($response);
        }
        $total_price = $sale_product->sale_price * $sale_product->quantity;
        $sale_product->total_price = $total_price;

        if ($sale_product->save()) {
            $response['status'] = true;
            $response['sale_product_id'] = $sale_product->id;
            return response()->json($response);
        } else {
            $response['status'] = false;
            $response['error'] = 'Failed to create sale product';
            return response()->json($response);
        }
    }

    public function destroy(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'sale_product_id' => 'required|integer|exists:sale_products,id',
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

        $sale_product = SaleProduct::findOrFail($data['sale_product_id']);
        $sale_product->delete();

        $response['status'] = true;
        $response['sale_product_id'] = $sale_product->id;
        return response()->json($response);
    }
}
