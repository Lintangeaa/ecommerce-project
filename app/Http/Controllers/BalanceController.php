<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\UserBalance;
use Auth;
use Midtrans\Config;
use Midtrans\CoreApi;

class BalanceController extends Controller
{
    public function showBalance()
    {
        $user = Auth::user();
        $balance = UserBalance::where('user_id', $user->id)->first();

        $count = Cart::where('user_id', $user->id)->get()->count();
        $order = Order::where('user_id', $user->id)->get();

        // Periksa apakah $balance ada sebelum mengakses propertinya
        $balanceAmount = $balance ? $balance->balance : 0;

        return view('home.balance', ['balance' => $balanceAmount], compact('count', 'order'));
    }



    public function __construct()
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is3ds');
    }

    public function topUp(Request $request)
    {
        $user = Auth::user();


    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'withdrawAmount' => 'required|numeric|min:1000',
        ]);

        $withdrawAmount = $request->withdrawAmount;

        // Buat permintaan penarikan saldo
        $withdrawRequest = Transaction::create([
            'user_id' => Auth::id(),
            'amount' => -$withdrawAmount,
            'status' => 'pending',
        ]);

        // Logic untuk menunggu konfirmasi admin bisa ditambahkan di sini

        return response()->json(['status' => 'success', 'message' => 'Permintaan withdraw berhasil diajukan, menunggu konfirmasi admin.']);
    }
}
