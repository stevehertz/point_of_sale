<?php

namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use App\Http\Resources\Organization as ResourcesOrganization;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class OrganizationsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        # code...
        if ($request->ajax()) {
            $data = Organization::where('user_id', Auth::user()->id)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('logo', function($row){
                    $logo = '<img src="'.asset('storage/organizations').'/'.$row['logo'].'" class="img-circle img-size-32 mr-2">';
                    return $logo;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="' . $row['id'] . '" class="btn btn-primary btn-sm selectBtn">Select</a>';
                    return $btn;
                })
                ->rawColumns(['action', 'logo'])
                ->make(true);
        }
        $request->session()->remove('organization');
        $page_title = "Organizations";
        $num_organizations = Organization::where('user_id', Auth::user()->id)->count();
        return view('organizations.dashboard.index', ['page_title' => $page_title, 'num_organizations' => $num_organizations]);
    }

    public function create()
    {
        # code...
        $page_title = 'New Company/Business';
        return view('organizations.dashboard.create', [
            'page_title' => $page_title,
        ]);
    }

    public function store(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'organization' => 'required|string',
            'tagline' => 'required|string',
            'email' => 'required|email|unique:organizations,email',
            'phone' => 'required|unique:organizations,phone',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg',
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

        // Handle icon upload
        if ($request->hasFile('logo')) {

            // file name with extension
            $logoNameWithExt = $request->file('logo')->getClientOriginalName();

            // Get Filename
            $logoName = pathinfo($logoNameWithExt, PATHINFO_FILENAME);

            // Get just Extension
            $extension = $request->file('logo')->getClientOriginalExtension();

            // Filename To store
            $logoNameToStore = $logoName . '_' . time() . '.' . $extension;

            // Upload Image
            $path = $request->file('logo')->storeAs('public/organizations', $logoNameToStore);
        }else{
            $logoNameToStore = 'noimage.png';
        }


        $organization = new Organization();

        $organization->user_id = Auth::user()->id;
        $organization->organization = $data['organization'];
        $organization->tagline = $data['tagline'];
        $organization->email = $data['email'];
        $organization->phone = $data['phone'];
        $organization->address = $data['address'];
        $organization->website = $data['website'];
        $organization->logo = $logoNameToStore;

        $organization->save();

        $response['status'] = true;
        $response['message'] = 'Organization successfully created';
        return response()->json($response);
    }

    public function show(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'organization_id' => 'required|exists:organizations,id',
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

        $organization = Organization::findOrFail($data['organization_id']);
        $request->session()->put('organization', $organization);
        return new ResourcesOrganization($organization);
    }
}
