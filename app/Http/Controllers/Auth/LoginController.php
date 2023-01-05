<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class LoginController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['guest']);
    }

    public function index()
    {
        # code...
        $page_title = 'Log in';
        return view('auth.login.index', ['page_title' => $page_title]);
    }

    public function store(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ], [
            'email.exists' => 'Email Entered doesnot exists. Check and try again.'
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

        $credentials = $request->only('email', 'password');
        $remember_me = $request->has('remember') ? true : false;

        if(Auth::attempt($credentials, $remember_me)){
            $response['status'] = true;
            $response['message'] = 'You have succesfully logged in to your account.';
            return response()->json($response);
        }else{
            $response['status'] = false;
            $response['error'] = 'Email address and password entered is invalid. Please check and try again..!';
            return response()->json($response);
        }

    }
}
