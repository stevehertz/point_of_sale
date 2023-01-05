<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
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
        $page_title = 'Profile';
        return view('back.users.index', ['page_title' => $page_title, 'organization' => $organization]);
    }

    public function update(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' =>  'required|string|max:255',
            'profile' => 'image|mimes:jpeg,png,jpg,gif,svg',
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

        if ($request->hasFile('profile')) {

            // file name with extension
            $imageNameWithExt = $request->file('profile')->getClientOriginalName();

            // Get Filename
            $imageName = pathinfo($imageNameWithExt, PATHINFO_FILENAME);

            // Get just Extension
            $extension = $request->file('profile')->getClientOriginalExtension();

            // Filename To store
            $imageNameToStore = $imageName . '_' . time() . '.' . $extension;

            // Upload Image
            $path = $request->file('profile')->storeAs('public/users', $imageNameToStore);
        }

        $user = User::findOrFail(Auth::user()->id);

        $user->id = $user->id;
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        if ($request->hasFile('profile')) {
            $user->profile = $imageNameToStore;
        }
        $user->gender = $data['gender'];
        $user->dob = $data['dob'];
        $user->save();

        $response['status'] = true;
        $response['message'] = 'Account updated';

        return response()->json($response);
    }

    public function update_password(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required', 'string', 'min:5'],
            'confirm_password' => ['required', 'string', 'min:5', 'same:new_password'],
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

        $user = User::findOrFail(Auth::user()->id);

        $user->id = $user->id;
        $user->password = Hash::make($data['new_password']);

        $user->save();

        $response['status'] = true;
        $response['message'] = 'Password updated';
        return response()->json($response);
    }

    public function logout()
    {
        # code...
        Auth::logout();
        return redirect()->route('auth.login.index');
    }
}
