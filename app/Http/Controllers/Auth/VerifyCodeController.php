<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Http\Request;
use Validator;

class VerifyCodeController extends Controller
{
    //
    public function __construct()
    {

        $this->middleware(['guest']);
    }

    public function index($id)
    {
        # code...
        $page_title = "Enter Code";
        $user = User::findOrFail($id);
        return view('auth.forgot.code', ['page_title' => $page_title, 'user' => $user]);
    }

    public function store(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'required|exists:users,id',
            'code' => 'required|exists:verifications,code',
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

        $user = User::findOrFail($data['user_id']);

        $userVerification = Verification::where('user_id', $user->id)->where('code', $data['code'])->limit(1)->first();

        if ($userVerification) {
            $userVerification->delete();
            // return successs rediretct to newpassword
            $response['status'] = true;
            $response['message']  = 'Your account has been verified. You can go a head and create a new password.';
            $response['user_id'] = $user->id;
            return response()->json($response);
        }

        $response['status'] = false;
        $response['error'] = 'The Verification Code entered is invalid. Please check and try again....';
        return response()->json($response);
    }
}
