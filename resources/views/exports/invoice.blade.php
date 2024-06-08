<!DOCTYPE html>
<html>

<head>
    <title>Invoice</title>
    <style type="text/css">
        /* Gaya CSS untuk invoice */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h1 {
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
            color: #007bff;
        }

        .status-lunas {
            background-color: #28a745;
            color: #fff;
            padding: 5px 10px;
            border-radius: 3px;
        }

        .status-not-lunas {
            background-color: #ebc034;
            color: #fff;
            padding: 5px 10px;
            border-radius: 3px;
        }

        .status-cancel {
            background-color: #eb3a34;
            color: #fff;
            padding: 5px 10px;
            border-radius: 3px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Invoice</h1>
        <p>Nomor Pesanan: {{ $order->id }}</p>
        <p>Tanggal Pesanan: {{ $order->created_at }}</p>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($order->orderProducts as $orderProduct)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $orderProduct->product->title }}</td>
                        <td>Rp. {{ number_format($orderProduct->product->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p class="total">Total Pembayaran: Rp. {{ number_format($order->total_payment, 2) }}</p>

        @if ($order->status != 'Menunggu Pembayaran')
            @if ($order->status === 'Pengemasan' || $order->status === 'Diterima')
                <p class="status-lunas">Status: Lunas</p>
            @elseif ($order->status === 'Dibatalkan')
                <p class="status-cancel">Status: Dibatalkan</p>
            @else
                <p class="status-not-lunas">Status: Belum Lunas</p>
            @endif
        @else
            <p class="status-not-lunas">Status: Belum Lunas</p>
        @endif
    </div>
</body>

</html>
