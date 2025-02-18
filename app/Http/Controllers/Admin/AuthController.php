<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->first(), 400);
        }
        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials)){
            $user = User::find(Auth::id());
            if($user){
                if($user->role != 'admin'){
                    return response()->json(['status'=>0,'message' => 'You are not admin'], 401);
                }
                else{
                    $token = $user->createToken('auth_token')->plainTextToken;
                    return response()->json(['status'=>1,'token' => $token], 200);
                }
            }
            else{
                return response()->json(['status'=>0,'message' => 'User not found'], 404);
            }
        }
        else{
            return response()->json(['status'=>0,'message' => 'Invalid Credentials'], 401);
        }
    }
}
