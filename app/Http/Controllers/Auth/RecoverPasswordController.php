<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RecoverPasswordController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['guest']);
    }

    public function index($id)
    {
        # code...
        $user = User::findOrFail($id);
        $page_title = "Recover Password";
        return view('auth.forgot.recover', [
            'user' => $user,
            'page_title' => $page_title,
        ]);
    }

    public function store(Request $request)
    {
        # code...
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'required|exists:users,id',
            'new_password' => 'required|min:5|max:30',
            'confirm_password' => 'required|same:new_password',
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

        $user = User::findOrFail($data['user_id']);

        $user->id = $user->id;
        $user->password = Hash::make($data['new_password']);

        $user->save();

        $response['status'] = true;
        $response['message'] = 'You have succesfully updated your password.';
        return response()->json($response);
    }
}
