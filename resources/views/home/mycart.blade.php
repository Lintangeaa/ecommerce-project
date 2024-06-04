<!DOCTYPE html>
<html>

<head>
    @include('home.css')

    <style type="text/css">
        .div_deg {
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
            text-align: center;
            color: white;
            font: 20px;
            font-weight: bold;
            background-color: black;
        }

        td {
            border: 2px solid black;
        }

        .cart_value {
            text-align: center;
            margin-bottom: 70px;
            padding: 18px;
        }

        .order_deg {
            padding-right: 100px;
            margin-top: -50px;
        }

        label {
            display: inline-block;
            width: 150px;
        }

        .div_gap {
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="hero_area">
        <!-- header section strats -->
        @include('home.header')
        <!-- end header section -->
    </div>

    @if ($count != 0)
        <div class="div_deg">
            <div class="order_deg">
                <form action="{{ url('confirm_order') }}" method="Post" onsubmit="calculateTotal()">
                    @csrf

                    <div class="div_gap">
                        <label>Nama</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}">
                    </div>

                    <div class="div_gap">
                        <label>Alamat</label>
                        <textarea name="address">{{ Auth::user()->address }}</textarea>
                    </div>

                    <div class="div_gap">
                        <label>Telepon</label>
                        <input type="text" name="phone" value="{{ Auth::user()->phone }}">
                    </div>

                    <div class="div_gap">
                        <input type="hidden" id="total_payment" name="total_payment" value="0">
                        <button class="btn btn-primary" id="pesan" type="submit">Pesan</button>
                    </div>

                </form>
            </div>

            <table>
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Gambar</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Remove</th>
                </tr>

                @foreach ($cart as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>Rp. {{ $item->price }}</td>
                        <td>
                            <img width="150" src="/products/{{ $item->image }}">
                        </td>
                        <td>{{ $item->qty }}</td>
                        <td class="item-total">Rp. {{ $item->total }}</td>

                        <td>
                            <a class="btn btn-danger" onclick="confirmation(event)"
                                href="{{ route('remove.cart', $item->product_id) }}">Cancel</a>
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>

        <div class="cart_value">
            <h3>Total Harga Rp. <span id="total_value"></span></h3>
        </div>
    @else
        <div class="container mt-5">
            <div class="text-center card">
                <div class="card-header">
                    Keranjang Belanja
                </div>
                <div class="card-body">
                    <h5 class="card-title">Keranjang Belanja Anda Kosong</h5>
                    <p class="card-text">Mulai berbelanja sekarang untuk menambahkan item ke keranjang Anda.</p>
                    <a href="/" class="btn btn-primary">Mulai Belanja</a>
                </div>
            </div>
        </div>
    @endif

    <!-- info section -->
    @include('home.footer')

    @include('admin.js')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            calculateTotal();
        });

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.item-total').forEach(function(element) {
                const itemTotal = parseFloat(element.textContent.replace('Rp. ', '').replace(',', ''));
                total += itemTotal;
            });
            document.getElementById('total_value').textContent = total.toFixed(2);
            document.getElementById('total_payment').value = total.toFixed(2);
        }
    </script>
</body>

</html>
