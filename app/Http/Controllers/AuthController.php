<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Passport\Token;
use App\User;
use App\Address;

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
        $address = new Address;
        $address->address = $request->address;
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        if($address->save())
        {
            $InsertedId = $address->id;
            $users = new User();
            $users->first_name = $request->first_name;
            $users->last_name = $request->last_name;
            $users->email = $request->email;
            $users->address_id = $InsertedId;
            $users->phone = $request->phone;
            $users->password =  Hash::make($request->password);
         $users->save();
        return response()->json(['message'=>"User Registered Successfully"],200);
        }
        else{
            return response()->json('error');
        }
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
        if (Gate::allows('isAdmin'))
        {
            return response()->json([
                'message'=>'Admin login successfully',
                'user'=> Auth::user(),
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
                )->toDateTimeString()
            ]);
        }
        elseif (Gate::allows('isUser'))
        {
            return response()->json([
                'message'=>'User login successfully',
                'user'=> Auth::user(),
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
                )->toDateTimeString()
            ]);
        }


    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json("User Logout Successfully");
    }
    public function user(Request $request)
    {
        return response()->json(
            [
            'data'=>$request->user(),
            'token'=>$request->user()->token()
            ]);
    }
}
