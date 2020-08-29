<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Passport\Token;
use App\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name'=> 'required|string',
            'last_name'=>'required|string',
            'email'=>'required|string|unique:users',
            'phone'=>'required|unique:users|min:10',
            'password'=>'required|string|min:6'
        ]);
        $users = new User([
            'first_name'=> $request->first_name,
            'last_name'=> $request->last_name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'password'=> Hash::make($request->password)
        ]);
        $users->save();
        return response()->json(['message'=>"User Registered Successfully"],200);

    }
    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required',
            'password'=>'required|string'
        ]);
        $credentials  = ['email'=>request('email'),'password' => request('password')] ;


        if(!Auth::attempt($credentials))
        {
            return response()->json(['message'=>"Unauthorized!"],400);
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'user'=> Auth::user(),
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);

    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json("User Logout Successfully");
    }
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
