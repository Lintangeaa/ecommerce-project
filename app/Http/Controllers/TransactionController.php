<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\UserBalance;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function createSnapToken(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();

        // Create a transaction record
        $transaction = Transaction::create([
            'code' => uniqid(),
            'user_id' => $user->id,
            'amount' => $validatedData['amount'],
            'status' => 'pending',
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

    public function handleWebhook(Request $request)
    {
        // Mendapatkan user yang terautentikasi
        $user = Auth::user();

        if (!$user) {
            return response()->json(['status' => 'unauthenticated'], 401);
        }

        // Memperbarui saldo pengguna berdasarkan user_id
        $userBalance = UserBalance::firstOrNew(['user_id' => $user->id]);

        // Mengupdate saldo jika transaksi berhasil

        $userBalance->balance += $request->amount;
        $userBalance->save();


        return response()->json(['status' => 'success']);
    }


}
