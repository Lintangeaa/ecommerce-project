<!DOCTYPE html>
<html>

<head>
    @include('home.css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <style type="text/css">
        .div_center {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 60px;
        }

        table {
            border: 2px solid black;
            text-align: center;
            width: 800px;
        }

        th {
            border: 2px solid black;
            background-color: black;
            color: white;
            font-size: 19px;
            font-weight: bold;
        }

        td {
            border: 2px solid black;
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="hero_area">
        <!-- header section starts -->
        @include('home.header')
        <!-- end header section -->

        <div class="div_center">
            <table>
                <tr>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Status Pengiriman</th>
                    <th>Pay Order</th>
                </tr>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->name }}</td>
                        <td>Rp. {{ number_format($order->total_payment, 2) }}</td>
                        <td>{{ $order->status }}</td>
                        <td>
                            @if ($order->status == 'Pengemasan')
                                <button type="button" class="btn btn-danger cancel-button"
                                    data-order-id="{{ $order->id }}"
                                    data-amount="{{ $order->total_payment }}">Cancel</button>
                            @elseif ($order->status == 'Menunggu Pembayaran')
                                <button type="button" class="btn btn-success pay-button"
                                    data-order-id="{{ $order->id }}"
                                    data-amount="{{ $order->total_payment }}">Bayar</button>
                            @else
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

        <!-- info section -->
        @include('home.footer')
    </div>

    <script>
        document.querySelectorAll('.pay-button').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                const amount = this.getAttribute('data-amount');

                fetch('/pay-order', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_id: orderId,
                            amount: amount
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pembayaran Berhasil',
                                    text: 'Pesanan Anda telah berhasil dibayar.',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    fetch('/webhook/orders', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({
                                                order_id: orderId,
                                                status: 'Pengemasan'
                                            })
                                        })
                                        .then(response => response.json())
                                        .then(responseData => {
                                            console.log(responseData);
                                            window.location.href = '/myorders';
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                        });
                                });
                            },
                            onPending: function(result) {
                                // Handle pending payment
                            },
                            onError: function(result) {
                                // Handle payment error
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
        document.querySelectorAll('.cancel-button').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                const amount = this.getAttribute('data-amount');

                Swal.fire({
                    title: 'Konfirmasi Pembatalan',
                    text: 'Apakah Anda yakin ingin membatalkan pesanan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Tidak, Kembali',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Lakukan pembatalan pesanan di sini
                        fetch('/webhook/midtrans', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    amount: amount
                                })
                            })
                            .then(response => response.json())
                            .then(responseData => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pembatalan Berhasil',
                                    text: 'Uang pembayaran masuk ke saldo anda',
                                    confirmButtonText: 'OK',
                                    timer: 5000
                                }).then(() => {
                                    fetch('/webhook/orders', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({
                                                order_id: orderId,
                                                status: 'Dibatalkan'
                                            })
                                        })
                                        .then(response => response.json())
                                        .then(responseData => {
                                            console.log(responseData);

                                            window.location.href = '/myorders';

                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                        });

                                });
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }
                });
            });
        });
    </script>
</body>

</html>
