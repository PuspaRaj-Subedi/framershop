<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\User;

class AuthController extends Controller
{
    public $successStatus = 200;

    public function register()
    {
        request()->validate([
            'first_name'=> 'required|string',
            'last_name'=>'required|string',
            'email'=>'required|string|unique:users',
            'phone'=>'required|unique:users|min:10',
            'password'=>'required|string|min:6'
        ]);
        $users = new User([
            'first_name'=> request('first_name'),
            'last_name'=> request('last_name'),
            'email'=>request('email'),
            'phone'=>request('phone'),
            'password'=> Hash::make(request('password')),
            'address_id'=> 1
        ]);
        if($users->save())
        {
            return response()->json(['data' => ' '], $this->successStatus);

        }
        else
        {
            return response()->json(['error' => 'Unauthorised'], 401);

        }

    }
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            if ($user->role == 3) {
                $user['token'] = $user->createToken('Xpress')->accessToken;
                return response()->json(['data' => $user], $this->successStatus);
            } else
                return response()->json(['error' => 'Unauthorised'], 401);

        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
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
            'token'=>$request->user()->token()->accessToken
            ]);
    }
}
