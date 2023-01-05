<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Validator;
use Illuminate\Http\Request;

class SettingsController extends Controller
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
        $page_title = "Settings";
        return view('back.settings.index', [
            'page_title' => $page_title,
            'organization' => $organization
        ]);
    }

    public function update(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'organization_id' => 'required|integer|exists:organizations,id',
            'organization' => 'required|string',
            'tagline' => 'required|string',
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
        }


        $organization = Organization::findOrFail($data['organization_id']);
        $organization->user_id = $organization->user_id;
        $organization->organization = $data['organization'];
        $organization->tagline = $data['tagline'];
        $organization->email = $organization->email;
        $organization->phone = $organization->phone;
        $organization->address = $data['address'];
        $organization->website = $data['website'];
        if ($request->hasFile('logo')) {
            $organization->logo = $logoNameToStore;
        }

        $organization->save();

        $response['status'] = true;
        $response['message'] = 'Organization successfully updated';
        return response()->json($response);
    }
}
