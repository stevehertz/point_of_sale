<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Organization;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoriesController extends Controller
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
            $data = Category::where('user_id', Auth::user()->id)->where('organization_id', $organization->id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $statusSpan = '';
                    if($row['status'] == 1){
                        $statusSpan .= '<span class="badge badge-success">Active</span>';
                    }else{
                        $statusSpan .= '<span class="badge badge-danger">Inactive</span>';
                    }
                    return $statusSpan;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="' . $row['id'] . '" class="btn btn-danger btn-sm deleteBtn"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        $page_title = 'Product Categories';
        return view('back.categories.index', [
            'page_title' => $page_title,
            'organization' => $organization,
        ]);
    }

    public function create($id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        $page_title = "New Category";
        return view('back.categories.create', [
            'page_title' => $page_title,
            'organization' => $organization,
        ]);
    }


    public function store(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'organization_id' => 'required|integer|exists:organizations,id',
            'category' => 'required|string|max:255',
            'status' => 'required|integer',
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

        $category = new Category();

        $category->user_id = Auth::user()->id;
        $category->organization_id = $data['organization_id'];
        $category->category = $data['category'];
        $category->slug = Str::slug($data['category']);
        $category->status = $data['status'];

        $category->save();

        $response['status'] = true;
        $response['message'] = 'Catgory created successfully';

        return response()->json($response);
    }

    public function destroy(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'category_id' => 'required|integer|exists:categories,id',
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

        $category = Category::findOrFail($data['category_id']);
        $category->delete();

        $response['status'] = true;
        $response['message'] = 'Catgory deleted successfully';

        return response()->json($response);
    }
}
