<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Product;
use Illuminate\Support\Str;



class ProductController extends Controller
{
    public $successStatus = 200;

    public function index($option)
    {

        switch ($option) {
            case 'all':
                $products = Product::get();
                break;
            default:
                $products = null;
        }
        return response()->json(['data' => $products], $this->successStatus);
    }

    public function details($id)
    {
        $orderdetails = Product::where('id', $id)
            ->get();
        return response()->json(['data' => $orderdetails], $this->successStatus);

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
            $products->user_id= Auth::id();
            $products->slug= str::slug($request->product_name, "-");
            if($products->save())
                return response()->json($this->successStatus);
            else
                return response()->json(['error' => 'Unauthorised'], 401);
        }
        else
            return response()->json(['error' => 'Unauthorised'], 401);


    }
    public function delete($product_id)
    {
        $products = Product::findOrFail($product_id);
        $products->delete();
        return response()->json($this->successStatus);
    }
}
