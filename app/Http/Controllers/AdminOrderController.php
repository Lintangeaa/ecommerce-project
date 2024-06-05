<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')
            ->with('orderProducts.product') // Mengambil produk melalui relasi orderProducts
            ->paginate(10);
        return view('admin.order', compact('orders'));
    }

    // public function show($id)
    // {
    //     $order = Order::findOrFail($id);
    //     return view('admin.orders.detail', compact('order'));
    // }
}
