<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\User;
use App\Model\Product;
use App\Model\Contact;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        $contact= Contact::All();
        return response()->json([
            'message'=> 'Contact For Delivery',
            'data'=> $contact
        ]);

    }
    public function store(Request $request)
    {
        $request->validate([
            'city'=>'required|string',
            'state'=>'required|string',
            'zipcode'=>'required|integer|min:5',
            'weight'=>'required|integer'
        ]);
        $contact = new Contact();
        $contact->user_name= Auth::user()->first_name.' '.Auth::user()->last_name;
        $contact->email= Auth::user()->email;
        $contact->phone= Auth::user()->phone;
        $contact->city= $request->city;
        $contact->state= $request->city;
        $contact->weight= $request->weight;
        $contact->zipcode= $request->zipcode;
        $contact->product_id= $request->id;
        $contact->save();
        return response()->json([
            'message'=>'Your Contact Is Submitted Succesfully',
            'info'=>$contact
            ]
        );
    }
    public function update($id)
    {

    }
}
