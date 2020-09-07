<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Facades\Auth;
use App\Model\Product;
use Illuminate\Support\Str;
use app\User;



class ProductController extends Controller
{
    public function index()
    {
        $products= Product::All();
        return response()->json([
            "data"=> $products,
            "message"=>'Display All Products',
        ]);
    }

    public function store(Request $request)
    {
        $products = new Product();
        $request->validate([
            'product_name'=>'required|string|',
            'description'=>'required|string|',
            'price'=>'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if ($request->hasFile('image')) {
            $imageName = time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('products'), $imageName);
            $products->product_url=$imageName;
            $products->product_name = $request->product_name;
            $products->Price=$request->price;
            $products->description= $request->description;
            $products->user_id= Auth::user()->id;
            $title= $request->product_name;
            $title = str::slug($title, "-");
            $products->slug= $title;
            $products->save();
            return response()->json([
                "success" => true,
                "message" => "Product successfully uploaded",
                "file" => $imageName
            ]);
            }

    }
    public function delete($product_id)
    {
        $products = Product::findOrFail($product_id);
        $products->delete();
        return response()->json('Product Deleted Succesfully');
    }
}
