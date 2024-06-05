<!DOCTYPE html>
<html>

<head>
    @include('admin.css')

    <style type="text/css">
        table {
            border: 2px solid white;
            text-align: center;
        }

        th {
            background-color: green;
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            color: white;
        }

        td {
            color: white;
            padding: 10px;
            border: 1px solid white;
        }

        .table_center {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        h1 {
            color: white;
            text-align: center;
            padding: 10px;
        }
    </style>

</head>

<body>

    @include('admin.header')
    @include('admin.sidebar')

    <div class="page-content">
        <div class="page-header">
            <div class="container-fluid">
                <h1>Halaman Pemesanan</h1>
                <div class="table_center">
                    <table>
                        <tr>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Nama Barang</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Detail Pesanan</th>
                            <th>Change Status</th>
                        </tr>

                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->name }}</td>
                                <td>{{ $order->rec_address }}</td>
                                <td>{{ $order->phone }}</td>
                                <td>
                                    @foreach ($order->orderProducts as $loopIndex => $orderProduct)
                                        {{ $loopIndex + 1 }}. {{ $orderProduct->product->title }}
                                        @if (!$loop->last)
                                            <br>
                                        @endif
                                    @endforeach
                                </td>

                                <td>Rp. {{ number_format($order->total_payment, 2) }}</td>
                                <td>
                                    @if ($order->status == 'in progress')
                                        <span style="color:red">{{ $order->status }}</span>
                                    @elseif($order->status == 'Dalam Perjalanan')
                                        <span style="color:skyblue">{{ $order->status }}</span>
                                    @else
                                        <span style="color:yellow">{{ $order->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-info" href="{{ url('order-detail', $order->id) }}">Detail</a>
                                </td>
                                <td>
                                    @if ($order->status == 'Pengemasan')
                                        <a class="btn btn-primary" href="{{ url('on_the_way', $order->id) }}">Dalam
                                            Perjalanan</a>
                                        <a class="btn btn-success"
                                            href="{{ url('delivered', $order->id) }}">Diterima</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('admin.js')

</body>

</html>
