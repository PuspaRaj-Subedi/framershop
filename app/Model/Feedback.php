<?php

namespace App\Model;
use App\Model\Product;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    function User(){
        return $this->belongsTo(User::class);
    }
    function Product()
    {
        return $this->belongsTo(Product::class);
    }
}
