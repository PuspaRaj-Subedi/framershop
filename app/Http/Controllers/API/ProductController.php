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

    public function store()
    {
        $products = new Product([
            'product_name' => request('product_name'),
            'Price' => request('Price'),
            'description' => request('description'),
            'user_id' => Auth::id(),
        ]);

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
