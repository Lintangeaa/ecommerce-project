<!-- Link Google Fonts untuk Roboto -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<!-- Link CSS lainnya -->

<style>
    body {
        font-family: 'Roboto', sans-serif;
        font-weight: bold;
    }
</style>

<header class="header_section">
    <nav class="navbar navbar-expand-lg custom_nav-container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <span>
                Ternak Gurami
            </span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ url('/') }}">Ternak Gurami <span
                            class="sr-only">(current)</span></a>
                </li>
            </ul>
            <div class="user_option">

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('myorders') }}">
                            Pesanan Saya
                        </a>

                        <a href="{{ url('mycart') }}">
                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                            ({{ $count }})
                        </a>

                        <!-- Link untuk Saldo -->
                        <a href="{{ route('balance') }}">
                            <i class="fa fa-wallet" aria-hidden="true"></i>
                            <span>
                                Saldo
                            </span>
                        </a>

                        <form style="padding: 10px" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <input class="btn btn-danger" type="submit" value="logout">
                        </form>
                    @else
                        <a href="{{ url('/login') }}">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <span>
                                Login
                            </span>
                        </a>

                        <a href="{{ url('/register') }}">
                            <i class="fa fa-vcard" aria-hidden="true"></i>
                            <span>
                                Register
                            </span>
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>
</header>
