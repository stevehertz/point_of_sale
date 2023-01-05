<?php

namespace App\Http\Controllers\Units;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProductUnitsController extends Controller
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
            $data = Unit::where('user_id', Auth::user()->id)
                ->where('organization_id', $organization->id)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteProductUnit"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $page_title = 'Product Units';
        $units = Unit::where('user_id', Auth::user()->id)
            ->where('organization_id', $organization->id)
            ->where('base_unit', '')
            ->latest()->get();
        return view('back.units.index', compact('page_title', 'organization', 'units'));
    }

    public function store(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'organization_id' => 'required|integer|exists:organizations,id',
            'name' => 'required|unique:units,name',
            'short_name' => 'required|string|unique:units,short_name',
            'operator' => 'required|string',
            'operation_value' => 'required|numeric',
        ]);

        if($validator->fails()){
            $response['status'] = false;
            $response['errors'] = $validator->errors()->all();
            return response()->json($response, 422);
        }

        $unit = new Unit();
        $unit->user_id = Auth::user()->id;
        $unit->organization_id = $data['organization_id'];
        $unit->name = $data['name'];
        $unit->short_name = $data['short_name'];
        if($request->base_unit){
            $unit->base_unit = $data['base_unit'];
        }else{
            $unit->base_unit = '';
        }
        $unit->operator = $data['operator'];
        $unit->operation_value = $data['operation_value'];

        if($unit->save()){
            $response['status'] = true;
            $response['message'] = 'Unit created successfully';
            return response()->json($response, 200);
        }else{
            $response['status'] = false;
            $response['message'] = 'Something went wrong';
            return response()->json($response, 500);
        }
    }

    public function show(Request $request)
    {
        # code...
        $data = $request->all();
        $validator = Validator::make($data, [
            'unit_id' => 'required|integer|exists:units,id',
        ]);

        if($validator->fails()){
            $response['status'] = false;
            $response['errors'] = $validator->errors()->all();
            return response()->json($response, 422);
        }

        $unit = Unit::findOrFail($data['unit_id']);
        $response['status'] = true;
        $response['unit'] = $unit;
        return response()->json($response, 200);
    }

    public function base_unit(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'unit_id' => 'required|integer|exists:units,id',
        ]);

        if($validator->fails()){
            $response['status'] = false;
            $response['errors'] = $validator->errors()->all();
            return response()->json($response, 422);
        }

        $unit = Unit::findOrFail($data['unit_id']);
        $unit_names = Unit::where('user_id', Auth::user()->id)
            ->where('organization_id', $unit->organization_id)
            ->where('base_unit', $unit->name)
            ->latest()->get();
        $response['status'] = true;
        $response['unit_names'] = $unit_names;
        return response()->json($response, 200);

    }

    public function destroy(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'unit_id' => 'required|integer|exists:units,id',
        ]);

        if($validator->fails()){
            $response['status'] = false;
            $response['errors'] = $validator->errors()->all();
            return response()->json($response, 422);
        }

        $unit = Unit::findOrFail($data['unit_id']);
        if($unit->delete()){
            $response['status'] = true;
            $response['message'] = 'Unit deleted successfully';
            return response()->json($response, 200);
        }else{
            $response['status'] = false;
            $response['message'] = 'Something went wrong';
            return response()->json($response, 500);
        }
    }
}
