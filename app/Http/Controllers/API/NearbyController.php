<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NearbyController extends Controller
{
    public function index() {

        $id = Auth::user()->address_id;
        $lat     = Address::where('id', $id)->get();
        $long   =  Address::where('id', $id)->get();
        $location=User::whereRaw(ACOS(SIN(deg2rad('latitude'))*SIN(deg2rad($lat))+COS(deg2rad('latitude'))*COS(deg2rad($lat))*COS(deg2rad('longitude')-deg2rad($long)))*6380 < 10);
       return response()->json($location);
    }
}
