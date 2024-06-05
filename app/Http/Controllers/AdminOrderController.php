<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserBalance;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword', '');
        $status = $request->input('status', '');

        $orders = Order::with('orderProducts.product', 'user')
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('user', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->when($status && $status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        return view('admin.orders.index', compact('orders', 'keyword', 'status'));
    }

    public function show($id)
    {
        $order = Order::with('orderProducts.product', 'user')->findOrFail($id);
        return view('admin.orders.detail', compact('order'));
    }

    public function changeStatus(Request $request)
    {
        $oid = $request->order_id;
        $status = $request->status;

        $order = Order::find($oid);

        if ($order) {
            // Update order status
            $order->status = $status;
            $order->save();

            // If order is cancelled, return balance to user
            if ($status == 'Dibatalkan') {
                $user = $order->user;
                $userBalance = UserBalance::firstOrCreate(['user_id' => $user->id]);
                $userBalance->balance += $order->total_payment;
                $userBalance->save();
            }

            toastr()->timeOut(5000)->closeButton()->addSuccess('Change Status Success');
        }

        return redirect()->route('admin.orders.index');
    }
}
