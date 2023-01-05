<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Organization;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class POSController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index($id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        $page_title = 'Point of Sale';
        $customers = Customer::where('user_id', Auth::user()->id)->where('organization_id', $organization->id)->latest()->get();
        $products = Product::where('user_id', Auth::user()->id)->where('organization_id', $organization->id)->latest()->get();
        $brands = Brand::where('user_id', Auth::user()->id)->where('organization_id', $organization->id)->latest()->get();
        $categories = Category::where('user_id', Auth::user()->id)->where('organization_id', $organization->id)->latest()->get();
        return view('back.pos.index', compact('organization', 'page_title', 'customers', 'products', 'brands', 'categories'));
    }
}
