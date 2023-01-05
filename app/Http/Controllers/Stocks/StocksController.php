<?php

namespace App\Http\Controllers\Stocks;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Product;
use App\Models\SaleProduct;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StocksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        # code..
        $organization = Organization::findOrFail($id);
        if($request->ajax()){
            $data = $organization->stock;
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('product_name', function($row){
                $product = Product::findOrFail($row->product_id);
                $product_name = $product->product;
                return $product_name;
            }) // add column product name
            ->rawColumns(['product_name'])
            ->make(true);
        }
        $page_title = "Stocks Summary";
        return view('back.stocks.index', [
            'page_title' => $page_title,
            'organization' => $organization,
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * stocks will be update per sale completed
     * @param sale_id required
     * Update stock per sale completed
     */
    public function update(Request $request)
    {
        //
        $data = $request->all();

        $validator = Validator::make($data, [
            'sale_product_id' => 'required|integer|exists:sale_products,id',
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

        $sale_product = SaleProduct::findOrFail($data['sale_product_id']);

        $product = Product::findOrFail($sale_product->product_id);

        $stock = Stock::where('organization_id', $sale_product->organization_id)
            ->where('product_id', $product->id)
            ->first();

        $quantity = $sale_product->quantity;

        $sold_stock = $stock->sold_stock + $quantity;

        $stock->sold_stock = $sold_stock;

        $closing_stock = $stock->total_stock - $stock->sold_stock;

        $stock->closing_stock = $closing_stock;

        if($stock->save()){
            $response['status'] = true;
            $response['message'] = 'Stock updated successfully';
        }else{
            $response['status'] = false;
            $response['error'] = 'Something went wrong';
        }

        return response()->json($response);


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * stocks will be update per sale completed
     * @param sale_id required
     * Update stock when a product is removed from a sale
     */
    public function update_deleted_stock(Request $request)
    {
        //
        $data = $request->all();

        $validator = Validator::make($data, [
            'sale_product_id' => 'required|integer|exists:sale_products,id',
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

        $sale_product = SaleProduct::findOrFail($data['sale_product_id']);

        $product = Product::findOrFail($sale_product->product_id);

        $stock = Stock::where('organization_id', $sale_product->organization_id)
            ->where('product_id', $product->id)
            ->first();

        $quantity = $sale_product->quantity;

        $sold_stock = $stock->sold_stock - $quantity;

        $stock->sold_stock = $sold_stock;

        $closing_stock = $stock->total_stock - $stock->sold_stock;

        $stock->closing_stock = $closing_stock;

        if($stock->save()){
            $response['status'] = true;
            $response['message'] = 'Stock updated successfully';
        }else{
            $response['status'] = false;
            $response['error'] = 'Something went wrong';
        }

        return response()->json($response);


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
