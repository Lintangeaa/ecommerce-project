<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    protected $fillable = ['title', 'image', 'price', 'quantity'];

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
