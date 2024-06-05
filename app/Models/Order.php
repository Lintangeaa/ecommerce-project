<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        // tambahkan kolom lain di sini sesuai kebutuhan
    ];

    // Relasi dengan user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan OrderProduct
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    // Relasi dengan Product melalui OrderProduct
    public function products()
    {
        return $this->hasManyThrough(Product::class, OrderProduct::class);
    }
}
