<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Supplier as ResourcesSupplier;
use App\Models\Organization;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SuppliersController extends Controller
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
            $data = Supplier::where('organization_id', $organization->id)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="' . $row['id'] . '" class="btn btn-primary btn-sm updateBtn"><i class="fa fa-edit"></i></a>';
                    $btn = $btn .= ' <a href="javascript:void(0)" id="' . $row['id'] . '" class="btn btn-danger btn-sm deleteBtn"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $page_title = 'Suppliers';
        return view('back.suppliers.index', [
            'page_title' => $page_title,
            'organization' => $organization,
        ]);
    }

    public function create($id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        $page_title = 'New Supplier';
        return view('back.suppliers.create', [
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
            'fullnames' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:suppliers,phone',
            'email' => 'required|string|email|max:255|unique:suppliers,email',
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

        $supplier = new Supplier();

        $supplier->organization_id = $data['organization_id'];
        $supplier->full_names = $data['fullnames'];
        $supplier->phone = $data['phone'];
        $supplier->email = $data['email'];
        $supplier->address = $data['address'];
        $supplier->balance = 0;

        if($supplier->save()){
            $response['status'] = true;
            $response['message'] = 'Supplier successfully added';
        }else{
            $response['status'] = false;
            $response['error'] = 'Something went wrong';
        }



        return response()->json($response);
    }

    public function show(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
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

        $supplier = Supplier::findOrFail($data['supplier_id']);
        $request->session()->put('supplier_id', $supplier->id);
        return new ResourcesSupplier($supplier);
    }

    public function edit(Request $request, $id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        if($request->session()->has('supplier_id')){
            $supplier = Supplier::findOrFail($request->session()->get('supplier_id')); // get the supplier from the session
            $request->session()->forget('supplier_id'); // remove the supplier from the session
            return view('back.suppliers.edit', [
                'page_title' => 'Edit Supplier',
                'organization' => $organization,
                'supplier' => $supplier,
            ]);
        }
        return redirect()->route('back.suppliers.index', $organization->id);
    }

    public function update(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'organization_id' => 'required|integer|exists:organizations,id',
            'fullnames' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:suppliers,phone,' . $data['supplier_id'],
            'email' => 'required|string|email|max:255|unique:suppliers,email,' . $data['supplier_id'],
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

        $supplier = Supplier::findOrFail($data['supplier_id']);

        $supplier->id = $supplier->id;
        $supplier->organization_id = $supplier->organization_id;
        $supplier->full_names = $data['fullnames'];
        $supplier->phone = $data['phone'];
        $supplier->email = $data['email'];
        $supplier->address = $data['address'];
        $supplier->balance = $supplier->balance;

        if($supplier->save()){
            $response['status'] = true;
            $response['message'] = 'Supplier successfully updated';
        }else{
            $response['status'] = false;
            $response['error'] = 'Something went wrong';
        }

        return response()->json($response);
    }

    public function destroy(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'supplier_id' => 'required|integer|exists:suppliers,id',
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

        $supplier = Supplier::findOrFail($data['supplier_id']);

        $supplier->delete();

        $response['status'] = true;
        $response['message'] = 'supplier successfully deleted';
        return response()->json($response);
    }
}
