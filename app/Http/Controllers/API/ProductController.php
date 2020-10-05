<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Product;
use App\Media;
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
                case 'my_order':
                $products = Order::where('receiver_id', Auth::id())->get();
                break;
                case 'my_product':
                $products = Product::where('user_id', Auth::id())->get();
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
        $medias = new Media();
        $request->validate([
            'product_name'=> 'required|string',
            'price'=>'required',
            'description'=>'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if ($request->hasFile('image')) {
            $title = $request->product_name;
            $imageName = $title.time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('products'), $imageName);
            $medias->product_url = $imageName;
            $medias->save();
            $products->product_id=$medias->id;
            $products->Price= $request->price;
            $products->user_id = Auth::user()->id;
            $products->description= $request->description;
            $products->product_name = $request->product_name;
            $products->slug= str::slug($request->product_name, "-");
            if ($products->save())
            return response()->json(['data' => 'success'], $this->successStatus);
            else
            return response()->json(['error' => 'Unauthorised'], 401);
            }
            else{
            return response()->json(['error' => 'Error'], 401);
            }





    }

    public function delete($product_id)
    {
        $products = Product::findOrFail($product_id);
        $products->delete();
        return response()->json($this->successStatus);
    }
}
