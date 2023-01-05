<?php

namespace App\Http\Controllers\Brands;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BrandsController extends Controller
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
            $data = Brand::where('user_id', Auth::user()->id)
                ->where('organization_id', $id)
                ->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('icon', function ($row) {
                    $iconImage = '<img src="'.asset('storage/brands').'/'.$row['icon'].'" alt="'.$row['brand'].'" class="img-circle img-size-32 mr-2">';
                    return $iconImage;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row['id'] . '" class="btn btn-danger btn-sm deleteBrandBtn"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'icon'])
                ->make(true);
        }
        $page_title = 'Brands';
        return view('back.brands.index', compact('page_title', 'organization'));
    }

    public function create($id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        $page_title = 'New Brands';
        return view('back.brands.create', compact('page_title', 'organization'));
    }

    public function store(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'organization_id' => 'required|integer|exists:organizations,id',
            'brand' => 'required|string|max:255',
            'icon' => 'image|mimes:jpeg,png,jpg,gif,svg|nullable',
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

        if ($request->has('icon')) {
            // file name with extension
            $iconNameWithExt = $request->file('icon')->getClientOriginalName();

            // Get Filename
            $iconName = pathinfo($iconNameWithExt, PATHINFO_FILENAME);

            // Get just Extension
            $extension = $request->file('icon')->getClientOriginalExtension();

            // Filename To store
            $iconNameToStore = $iconName . '_' . time() . '.' . $extension;

            // Upload Image
            $path = $request->file('icon')->storeAs('public/brands', $iconNameToStore);
        } else {
            $iconNameToStore = 'noimage.png';
        }


        $brand = new Brand();

        $brand->user_id = Auth::user()->id;
        $brand->organization_id = $data['organization_id'];
        $brand->brand = $data['brand'];
        $brand->icon = $iconNameToStore;

        $brand->save();

        $response['status'] = true;
        $response['message'] = 'Brand Created Successfully';

        return response()->json($response);
    }

    public function destroy(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'brand_id' => 'required|integer|exists:brands,id',
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

        $brand = Brand::findOrFail($data['brand_id']);

        if($brand->icon != 'noimage.png'){
            Storage::delete('public/brands/'.$brand->icon);
        }

        $brand->delete();

        $response['status'] = true;
        $response['message'] = 'Brand Deleted Successfully';

        return response()->json($response);

    }
}
