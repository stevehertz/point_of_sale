<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class RegistrationController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['guest']);
    }

    public function index()
    {
        # code...
        $page_title = 'Registration';
        return view('auth.register.index', ['page_title' => $page_title]);
    }

    public function store(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|max:30',
            'confirm_password' => 'required|same:password',
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

        $user = new User();

        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->profile = 'noimage.png';
        $user->phone = $data['phone'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);

        $user->save();

        event(new Registered($user));

        Auth::login($user);

        $response['status'] = true;
        $response['message'] = 'Accounted Successfully Created.';

        return response()->json($response);
    }
}
