<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Organization;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Models\Stock;
use App\Models\Unit;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Picqer;

class ProductsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *1,
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        //
        $organization = Organization::findOrFail($id);
        if ($request->ajax()) {
            $data = Product::join('categories', 'products.category_id', '=', 'categories.id')
                ->join('brands', 'products.brand_id', '=', 'brands.id')
                ->join('units', 'products.unit_id', '=', 'units.id')
                ->select('products.*', 'categories.category as category_name', 'brands.brand as brand_name', 'units.short_name as short_name')
                ->where('products.user_id', Auth::user()->id)
                ->where('products.organization_id', $organization->id)
                ->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('view', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="' . $row['id'] . '" class="btn btn-default btn-sm viewBtn"><i class="fa fa-eye"></i></a>';
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="' . $row['id'] . '" class="btn btn-primary btn-sm editBtn"><i class="fa fa-edit"></i></a>';
                    $btn = $btn . ' <a href="javascript:void(0)" id="' . $row['id'] . '" class="btn btn-danger btn-sm deleteBtn"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['view', 'action'])
                ->make(true);
        }
        $request->session()->remove('product_id');
        $page_title = 'Products';
        return view('back.products.index', [
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
        $categories = Category::where('user_id', Auth::user()->id)
            ->where('organization_id', $organization->id)
            ->where('status', 1)->latest()->get();
        $brands = Brand::where('user_id', Auth::user()->id)
            ->where('organization_id', $organization->id)
            ->latest()->get();
        $units = Unit::where('user_id', Auth::user()->id)
            ->where('organization_id', $organization->id)
            ->where('base_unit', '')->latest()->get();
        $page_title = 'New Product';
        return view('back.products.create', [
            'page_title' => $page_title,
            'organization' => $organization,
            'categories' => $categories,
            'brands' => $brands,
            'units' => $units,
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
            'brand_id' => 'required|integer|exists:brands,id',
            'unit_id' => 'required|integer|exists:units,id',
            'product_name' => 'required|string|max:255',
            'product_code' => 'required|string|max:255|unique:products,product_code',
            'category_id' => 'required|integer|exists:categories,id',
            'product_type' => 'required|string|max:255',
            'selling_price' => 'required|numeric',
            'stocks' => 'required|integer',
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


        $product = new Product;

        $product_code = $data['product_code'];
        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
        $barcode = $generator->getBarcode($product_code, $generator::TYPE_CODE_128);  //generate barcode

        $product->user_id = Auth::user()->id;
        $product->organization_id = $data['organization_id'];
        $product->product = $data['product_name'];
        $product->category_id = $data['category_id'];
        $product->product_type = $data['product_type'];
        $product->product_code = $data['product_code'];
        $product->barcodes = $barcode;
        $product->purchase_price = $data['purchase_price'];
        $product->selling_price = $data['selling_price'];
        $product->stocks = $data['stocks'];
        $product->brand_id = $data['brand_id'];
        $product->unit_id = $data['unit_id'];
        $product->sale_unit = $data['sale_unit'];
        $product->purchase_unit = $data['purchase_unit'];

        if ($product->save()) {

            $stock = new Stock;

            $stock->user_id = Auth::user()->id;
            $stock->organization_id = $data['organization_id'];
            $stock->product_id = $product->id;
            $stock->opening_stock = $data['stocks'];
            $stock->purchased_stock = 0;
            $total_stock = $stock->opening_stock + $stock->purchased_stock;
            $stock->total_stock = $total_stock;
            $stock->sold_stock = 0;
            $closing_stock = $stock->total_stock - $stock->sold_stock;
            $stock->closing_stock = $closing_stock;

            if ($stock->save()) {
                $response['status'] = true;
                $response['message'] = 'Product added successfully';
                return response()->json($response);
            } else {
                $response['status'] = false;
                $response['message'] = 'Something went wrong';
                return response()->json($response);
            }
        } else {
            $response['status'] = false;
            $response['message'] = 'Something went wrong';
            return response()->json($response);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
        $data = $request->all();

        $validator = Validator::make($data, [
            'product_id' => 'required|integer|exists:products,id',
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

        $product = Product::findOrFail($data['product_id']);
        $request->session()->put('product_id', $product->id);
        $response['status'] = true;
        $response['product'] = $product;
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function view(Request $request, $id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        if ($request->session()->has('product_id')) {
            $product = Product::findOrFail($request->session()->get('product_id'));
            $category = $product->category;
            $brand = $product->brand;
            $unit = $product->unit;
            $request->session()->forget('product_id');
            $page_title = 'View Product';
            return view('back.products.view', [
                'page_title' => $page_title,
                'organization' => $organization,
                'product' => $product,
                'category' => $category,
                'brand' => $brand,
                'unit' => $unit,
            ]);
        } else {
            return redirect()->route('back.products.index', $organization->id);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print(Request $request, $id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        if ($request->session()->has('product_id')) {
            $product = Product::findOrFail($request->session()->get('product_id'));
            $category = $product->category;
            $brand = $product->brand;
            $unit = $product->unit;
            $request->session()->forget('product_id');
            $page_title = 'View Product';
            return view('back.products.print', [
                'page_title' => $page_title,
                'organization' => $organization,
                'product' => $product,
                'category' => $category,
                'brand' => $brand,
                'unit' => $unit,
            ]);
        } else {
            return redirect()->route('back.products.index', $organization->id);
        }
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
        if ($request->session()->has('product_id')) {
            $product = Product::findOrFail($request->session()->get('product_id'));
            $category = $product->category;
            $brand = $product->brand;
            $unit = $product->unit;
            $categories = Category::where('user_id', Auth::user()->id)
                ->where('organization_id', $organization->id)
                ->where('status', 1)->latest()->get();
            $brands = Brand::where('user_id', Auth::user()->id)
                ->where('organization_id', $organization->id)
                ->latest()->get();
            $units = Unit::where('user_id', Auth::user()->id)
                ->where('organization_id', $organization->id)
                ->where('base_unit', '')->latest()->get();
            $request->session()->forget('product_id');
            $page_title = 'Edit Product';
            return view('back.products.update', [
                'page_title' => $page_title,
                'organization' => $organization,
                'product' => $product,
                'category' => $category,
                'brand' => $brand,
                'unit' => $unit,
                'categories' => $categories,
                'brands' => $brands,
                'units' => $units,
            ]);
        } else {
            return redirect()->route('back.products.index', $organization->id);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $data = $request->all();

        $validator = Validator::make($data, [
            'product_id' => 'required|integer|exists:products,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'unit_id' => 'required|integer|exists:units,id',
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'product_type' => 'required|string|max:255',
            'selling_price' => 'required|numeric',
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

        $product = Product::findOrFail($data['product_id']);

        $product_code = $product->product_code;
        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
        $barcode = $generator->getBarcode($product_code, $generator::TYPE_CODE_128);  //generate barcode

        $product->id = $product->id;
        $product->user_id = $product->user_id;
        $product->organization_id = $product->organization_id;
        $product->product = $data['product_name'];
        $product->category_id = $data['category_id'];
        $product->product_type = $data['product_type'];
        $product->product_code = $product->product_code;
        $product->barcodes = $barcode;
        $product->purchase_price = $data['purchase_price'];
        $product->selling_price = $data['selling_price'];
        $product->stocks = $product->stocks;
        $product->brand_id = $data['brand_id'];
        $product->unit_id = $data['unit_id'];
        $product->sale_unit = $data['sale_unit'];
        $product->purchase_unit = $data['purchase_unit'];

        if ($product->save()) {
            $response['status'] = true;
            $response['message'] = 'Product updated successfully';
            return response()->json($response);
        } else {
            $response['status'] = false;
            $response['message'] = 'Something went wrong';
            return response()->json($response);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * Update stocks in products from a sale
     * @param sale_id
     * This deducts stocks by quantity as they are added on a sale
     */
    public function update_stocks(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'sale_product_id' => 'required|integer|exists:sale_products,id'
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

        $quantity = $sale_product->quantity;

        $product->id = $product->id;

        $product->stocks = $product->stocks - $quantity;

        if($product->save()){
            $response['status'] = true;
            $response['product_id'] = $product->id;
            return response()->json($response);
        }else{
            $response['status'] = false;
            $response['error'] = 'Something went wrong';
            return response()->json($response);
        }
    }

     /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * Update stocks in products from a sale
     * @param sale_id
     * This deducts stocks by quantity as they are removed from a sale
     */
    public function update_deleted_stocks(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'sale_product_id' => 'required|integer|exists:sale_products,id'
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

        $quantity = $sale_product->quantity;

        $product->id = $product->id;

        $product->stocks = $product->stocks + $quantity;

        if($product->save()){
            $response['status'] = true;
            $response['product_id'] = $product->id;
            return response()->json($response);
        }else{
            $response['status'] = false;
            $response['error'] = 'Something went wrong';
            return response()->json($response);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $data = $request->all();

        $validator = Validator::make($data, [
            'product_id' => 'required|integer|exists:products,id',
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

        $product = Product::findOrFail($data['product_id']);

        if($product->delete()){

            $stock = Stock::where('product_id', $product->id)->first();

            if($stock){
                if($stock->delete()){
                    $response['status'] = true;
                    $response['message'] = 'Product successfully deleted';
                    return response()->json($response);
                }
            }

        }


    }
}
