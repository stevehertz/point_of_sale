<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer as ResourcesCustomer;
use App\Models\Customer;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CustomersController extends Controller
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
            $data = Customer::where('user_id', Auth::user()->id)
                ->where('organization_id', $id)
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteCustomerBtn"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $page_title = 'Customers';
        return view('back.customers.index', [
            'page_title' => $page_title,
            'organization' => $organization,
        ]);
    }

    public function create($id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        $page_title = 'New Customer';
        return view('back.customers.create', [
            'page_title' => $page_title,
            'organization' => $organization,
        ]);
    }

    public function store(Request $request)
    {
        # code...
        $data = $request->all();

        if ($data['type'] == 'new_customer') {
            $validator = Validator::make($data, [
                'organization_id' => 'required|integer|exists:organizations,id',
                'full_names' => 'required|string|max:255',
                'phone' => 'required|string|max:255|unique:customers,phone',
                'email' => 'required|string|max:255|unique:customers,email',
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

            $customer = new Customer();

            $customer->user_id = Auth::user()->id;
            $customer->organization_id = $data['organization_id'];
            $customer->full_names = $data['full_names'];
            $customer->phone = $data['phone'];
            $customer->email = $data['email'];
            $customer->address = $data['address'];

            $customer->save();

            $response['status'] = true;
            $response['message'] = 'Customer successfully created';

            return response()->json($response);
        }else{
            // make sure organization id is valid
            $validator = Validator::make($data, [
                'organization_id' => 'required|integer|exists:organizations,id',
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
            // make sure walk in is not entered twice
            $walkin_customer = Customer::where('user_id', Auth::user()->id)
                                        ->where('organization_id', $data['organization_id'])
                                        ->where('full_names', 'Walk-in')
                                        ->limit(1)->first();

            if($walkin_customer){
                $response['status'] = false;
                $response['error'] = 'Walk-in customer already exists';
                return response()->json($response);
            }

            $customer = new Customer();

            $customer->user_id = Auth::user()->id;
            $customer->organization_id = $data['organization_id'];
            $customer->full_names = 'Walk-in';
            $customer->phone = '';
            $customer->email = '';
            $customer->address = '';
            $customer->balance = '0.00';

            $customer->save();

            $response['status'] = true;
            $response['message'] = 'Walk-in customer successfully created';
            return response()->json($response);
        }
    }

    public function show(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'customer_id' => 'required|integer|exists:customers,id',
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

        $customer = Customer::findOrFail($data['customer_id']);
        return new ResourcesCustomer($customer);
    }

    public function update(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'customer_id' => 'required|integer|exists:customers,id',
            'full_names' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:customers,phone,' . $data['customer_id'],
            'email' => 'required|string|max:255|unique:customers,email,' . $data['customer_id'],
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

        $customer = Customer::findOrFail($data['customer_id']);

        $customer->full_names = $data['full_names'];
        $customer->phone = $data['phone'];
        $customer->email = $data['email'];
        $customer->address = $data['address'];

        $customer->save();

        $response['status'] = true;
        $response['message'] = 'Customer successfully updated';

        return response()->json($response);
    }

    public function destroy(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'customer_id' => 'required|integer|exists:customers,id',
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

        $customer = Customer::findOrFail($data['customer_id']);

        $customer->delete();

        $response['status'] = true;
        $response['message'] = 'Customer successfully deleted';

        return response()->json($response);
    }
}
