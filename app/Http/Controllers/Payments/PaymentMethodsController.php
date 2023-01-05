<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class PaymentMethodsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);
        if($request->ajax()){
            $data = PaymentMethod::where('user_id', Auth::user()->id)
                                ->where('organization_id', $organization->id)
                                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="' . $row['id'] . '" class="btn btn-danger btn-sm deleteBtn"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $page_title = 'Payment Methods';
        return view('back.methods.index', compact('page_title', 'organization'));
    }

    public function store(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'organization_id' => 'required|integer|exists:organizations,id',
            'method' => 'required|string|max:255',
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

        $paymentMethod = new PaymentMethod();

        $paymentMethod->user_id = Auth::user()->id;
        $paymentMethod->organization_id = $data['organization_id'];
        $paymentMethod->method = $data['method'];
        $paymentMethod->slug = Str::slug($data['method']);

        $paymentMethod->save();

        $response['status'] = true;
        $response['message'] = 'Method added successfully';
        return response()->json($response);

    }

    public function destroy(Request $request)
    {
        # code...
        $data = $request->all();
        $validator = Validator::make($data, [
            'method_id' => 'required|integer|exists:payment_methods,id',
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

        $paymentMethod = PaymentMethod::findOrFail($data['method_id']);
        $paymentMethod->delete();

        $response['status'] = true;
        $response['message'] = 'Method deleted successfully';

        return response()->json($response);
    }
}
