<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
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
}
