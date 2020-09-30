<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Order;
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
        $request->validate([
            'product_name' => 'required',
            'description' => 'required',
            'price' => 'required',
        ]);

        $products->product_url = 'https://www.irishtimes.com/polopoly_fs/1.3594671.1534163385!/image/image.jpg_gen/derivatives/box_620_330/image.jpg';
        $products->product_name = $request->product_name;
        $products->Price = $request->Price;
        $products->description = $request->description;
        $products->user_id = Auth::id();
        $products->slug = str::slug($request->product_name, "-");
        if ($products->save())
            return response()->json(['data' => 'success'], $this->successStatus);
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
