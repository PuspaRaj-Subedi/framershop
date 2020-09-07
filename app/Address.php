<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    protected $fillable = [
        'address','latitude', 'longitude',
    ];
    public function user()
    {
            return $this->hasMany(User::class);
    }

}
