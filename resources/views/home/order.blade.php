<!DOCTYPE html>
<html>

<head>
    @include('home.css')
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <style>
        <style type="text/css">.div_center {
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
                    <th>Gambar</th>
                    <th>Pay Order</th>
                </tr>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->total_payment }}</td>
                        <td>{{ $order->status }}</td>

                        <td>
                            <form id="payment-form-{{ $order->id }}" method="POST" action="{{ route('pay-order') }}">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <input type="hidden" name="amount" value="{{ $order->total_payment }}">
                                <button type="button" class="btn btn-primary"
                                    onclick="payWithSnap({{ $order->id }})">Pay Order</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>

        </div>

        <!-- info section -->
        @include('home.footer')
    </div>

    <!-- Midtrans Snap script -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script>
        function payWithSnap(orderId) {
            var formId = 'payment-form-' + orderId;
            var form = document.getElementById(formId);
            var formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            // Redirect to a success page or handle success event
                            console.log('Payment success:', result);
                        },
                        onPending: function(result) {
                            // Redirect to a pending page or handle pending event
                            console.log('Payment pending:', result);
                        },
                        onError: function(result) {
                            // Handle error event
                            console.error('Payment error:', result);
                        }
                    });
                })
                .catch(error => {
                    console.error('Payment error:', error);
                });
        }
    </script>
</body>

</html>
