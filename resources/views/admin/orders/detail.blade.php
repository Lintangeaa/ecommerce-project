<!-- resources/views/admin/orders/show.blade.php -->
<!DOCTYPE html>
<html>

<head>
    @include('admin.css')

    <style type="text/css">
        .order-details {
            color: white;
            margin: 20px;
            padding: 20px;
            background-color: #333;
            border-radius: 8px;
        }

        .order-details h2 {
            color: #4CAF50;
        }

        .order-details table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .order-details th,
        .order-details td {
            border: 1px solid #4CAF50;
            padding: 10px;
            text-align: left;
        }

        .order-details th {
            background-color: #4CAF50;
            color: white;
        }

        .order-details td {
            background-color: #555;
        }
    </style>
</head>

<body>

    @include('admin.header')
    @include('admin.sidebar')

    <div class="page-content">
        <div class="container-fluid">
            <div class="order-details">
                <h2>Detail Pesanan</h2>
                <p><strong>Nama:</strong> {{ $order->user->name }}</p>
                <p><strong>Alamat:</strong> {{ $order->rec_address }}</p>
                <p><strong>Telepon:</strong> {{ $order->phone }}</p>
                <p><strong>Status:</strong> {{ $order->status }}</p>

                <h3>Produk dalam Pesanan:</h3>
                <table>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                    </tr>
                    @foreach ($order->orderProducts as $index => $orderProduct)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $orderProduct->product->title }}</td>
                            <td>Rp. {{ number_format($orderProduct->product->price, 2) }}</td>
                            <td>{{ $orderProduct->quantity }}</td>
                            <td>Rp. {{ number_format($orderProduct->product->price * $orderProduct->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </table>

                <h3>Total Pembayaran:</h3>
                <p><strong>Rp. {{ number_format($order->total_payment, 2) }}</strong></p>

                <a class="btn btn-primary" href="{{ route('admin.orders.index') }}">Kembali ke Daftar Pesanan</a>
            </div>
        </div>
    </div>
    @include('admin.js')

</body>

</html>
