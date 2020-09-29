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

        $user = User::with('Address')->get();
        dd($user);
        $lat = Auth::User()->Address->latitude;
        $long= Auth::User()->Address->longitude;

        $location = User::with('Address')
        ->whereRaw('6371 * acos( cos( radians(28.2096) ) * cos( radians( Address->latitude ) ) * cos( radians( longitude ) - radians(83.4596) ) + sin( radians(28.2096) ) * sin( radians( Address->latitude ) ) ) )  <5')
        ->get();

       return response()->json([
           'user as login'=>$location,
           'user' => $user,
           'latitude'=>$lat,
           'longitude'=>$long
           ]);
    }
}
