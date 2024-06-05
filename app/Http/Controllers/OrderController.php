<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use App\Models\UserBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function payOrder(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();
        $order = Order::find($validatedData['order_id']);

        // Create a transaction record
        $transaction = Transaction::create([
            'code' => uniqid(),
            'user_id' => $user->id,
            'amount' => $validatedData['amount'],
            'status' => 'pending',
            'order_id' => $order->id,
        ]);

        // Prepare transaction details for Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $transaction->code,
                'gross_amount' => $transaction->amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
        ];

        // Generate Snap token
        $snapToken = Snap::getSnapToken($params);

        return response()->json(['snap_token' => $snapToken]);

    }

    public function handleOrder(Request $request)
    {
        // Mendapatkan user yang terautentikasi
        $user = Auth::user();

        if (!$user) {
            return response()->json(['status' => 'unauthenticated'], 401);
        }

        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|string',
        ]);

        $order = Order::find($validatedData['order_id']);

        // Check if the order belongs to the authenticated user
        if (!$order || $order->user_id !== $user->id) {
            return response()->json(['status' => 'error', 'message' => 'Order not found or unauthorized'], 404);
        }

        // Update the order status
        $order->status = $validatedData['status'];
        $order->save();

        return response()->json(['status' => 'success']);
    }

    public function payWithBalance(Request $request)
    {
        $user = Auth::user();
        $userBalance = UserBalance::where('user_id', $user->id)->first();
        $order = Order::find($request->order_id);

        if ($userBalance && $userBalance->balance >= $order->total_payment) {
            // Deduct balance
            $userBalance->balance -= $order->total_payment;
            $userBalance->save();

            // Update order status
            $order->status = 'Pengemasan';
            $order->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Saldo tidak mencukupi']);
        }
    }



}
