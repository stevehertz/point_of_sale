<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\UserEmail;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;

class ForgotPassController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['guest']);
    }

    public function index()
    {
        # code...
        $page_title = 'Forgot Password';
        return view('auth.forgot.index', [
            'page_title' => $page_title,
        ]);
    }

    public function store(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email Entered doesnot exists. Check and try again.',
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

        $code = mt_rand(1000, 9999);

        $user = User::where('email', $data['email'])->limit(1)->first();

        $current_verification = $user->verification;

        if ($current_verification) {
            $verification = $current_verification;

            $verification->id = $verification->id;
            $verification->user_id = $user->id;
            $verification->code = $code;

            $verification->save();
        } else {

            $verification = new Verification();

            $verification->user_id = $user->id;
            $verification->code = $code;

            $verification->save();
        }

        $details = [
            'title' => 'Welcome to Schedulize POS',
            'body' => 'We have received your request to change your password. Please enter the code below to continue: ' . $verification->code,
        ];

        Mail::to($user->email)->send(new UserEmail($details));

        $response['status'] = true;
        $response['message'] = 'An email is on its way to you. Enter the auth code to continue...';
        $response['user_id'] = $user->id;
        return response()->json($response);
    }
}
