<!DOCTYPE html>
<html>

<head>
    @include('admin.css')

    <style type="text/css">
        table {
            border: 2px solid white;
            text-align: center;
            width: 100%;
            margin: 20px 0;
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
            flex-direction: column;
        }

        h1 {
            color: white;
            text-align: center;
            padding: 20px;
        }

        .filter-form {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .filter-form input,
        .filter-form select,
        .filter-form button {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .filter-form button {
            background-color: #4CAF50;
            color: white;
            border: none;
        }

        .pagination {
            display: flex;
            justify-content: center;
            padding: 20px 0;
        }

        .pagination a,
        .pagination span {
            margin: 0 5px;
            padding: 8px 16px;
            border: 1px solid #ccc;
            color: #333;
            text-decoration: none;
        }

        .pagination .active span {
            background-color: #4CAF50;
            color: white;
            border-color: #4CAF50;
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

                <form action="{{ route('admin.orders.index') }}" method="GET" class="filter-form">
                    <input type="text" name="keyword" value="{{ $keyword }}"
                        placeholder="Cari berdasarkan nama...">
                    <select name="status">
                        <option value="">Semua Status</option>
                        <option value="Pengemasan" {{ $status == 'Pengemasan' ? 'selected' : '' }}>Pengemasan</option>
                        <option value="Menunggu Pembayaran" {{ $status == 'Menunggu Pembayaran' ? 'selected' : '' }}>
                            Menunggu Pembayaran</option>
                        <option value="Dibatalkan" {{ $status == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        <option value="Dikirimkan" {{ $status == 'Dikirimkan' ? 'selected' : '' }}>Dikirimkan</option>
                        <option value="Diterima" {{ $status == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                    </select>
                    <button type="submit">Cari</button>
                </form>

                <div class="export-buttons" style="text-align: center; margin-bottom: 20px;">
                    <a href="{{ route('admin.orders.exportExcel') }}" class="btn btn-success">Export to Excel</a>
                    <a href="{{ route('admin.orders.exportPDF') }}" class="btn btn-danger">Export to PDF</a>
                </div>

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
                                <td>{{ $order->user->name }}</td>
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
                                    <a class="btn btn-info"
                                        href="{{ route('admin.orders.show', $order->id) }}">Detail</a>
                                </td>
                                <td>
                                    <form action="{{ route('admin.orders.changeStatus') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <select name="status" onchange="this.form.submit()">
                                            <option value="Pengemasan"
                                                {{ $order->status == 'Pengemasan' ? 'selected' : '' }}>Pengemasan
                                            </option>
                                            <option value="Menunggu Pembayaran"
                                                {{ $order->status == 'Menunggu Pembayaran' ? 'selected' : '' }}>
                                                Menunggu Pembayaran</option>
                                            <option value="Dibatalkan"
                                                {{ $order->status == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan
                                            </option>
                                            <option value="Dikirimkan"
                                                {{ $order->status == 'Dikirimkan' ? 'selected' : '' }}>Dikirimkan
                                            </option>
                                            <option value="Diterima"
                                                {{ $order->status == 'Diterima' ? 'selected' : '' }}>Diterima
                                            </option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                <div class="pagination">
                    {{ $orders->appends(['keyword' => $keyword, 'status' => $status])->links() }}
                </div>

            </div>
        </div>
    </div>
    @include('admin.js')

</body>

</html>
