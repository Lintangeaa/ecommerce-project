<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\UserBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Snap;
use Dompdf\Dompdf;
use Dompdf\Options;
use Maatwebsite\Excel\Facades\Excel;


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

    public function generateInvoice($orderId)
    {
        // Mengambil detail pesanan
        $order = Order::findOrFail($orderId);

        // Menggunakan Dompdf untuk membuat PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);
        $html = view('exports/invoice', compact('order'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Menghasilkan nama file PDF
        $filename = 'invoice_' . $orderId . '.pdf';

        // Mengirimkan file PDF ke browser
        return $dompdf->stream($filename);
    }

    public function exportExcel(Request $request)
    {
        $orders = Order::with('user', 'orderProducts.product')->get();
        return Excel::download(new OrdersExport($orders), 'orders.xlsx');
    }

    public function exportPDF(Request $request)
    {
        $orders = Order::with('user', 'orderProducts.product')->get();
        $pdf = $this->generatePDF($orders);
        return response()->streamDownload(
            fn() => print ($pdf),
            'orders.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }

    protected function generatePDF($orders)
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $html = view('/exports/admin/order', compact('orders'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

}
