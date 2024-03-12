<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Larevel\Fortify\Fortify;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if ($validator->fails()) {
            $response["message"] = $validator->errors();
            return response()->json($response, 400);
        }

        $user = User::where('email', $request->email)->first();

        if ($user &&
            Hash::check($request->password, $user->password)) {
            $response["isValide"] = true;
            $response["user"] = array(
                "email" => $user->email,
                "firstname" => $user->firstname,
                "lastname" => $user->lastname,
            );
            return response()->json($response);
        }

        $response["isValide"] = false;
        $response["message"] = array("failure" => trans('auth.failed'));
        return response()->json($response);       
    }
}