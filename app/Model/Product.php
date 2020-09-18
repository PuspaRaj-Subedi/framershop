<?php

namespace App\Model;
use Laravel\Passport\HasApiTokens;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\Feedback;

class Product extends Model
{
    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Feedback()
    {
        return $this->hasMany(Feedback::class);
    }
}
