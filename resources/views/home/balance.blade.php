<!DOCTYPE html>
<html>

<head>
    <title>Top Up Saldo</title>
    @include('home.css')
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <style>
        /* Styles for the modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }
    </style>
</head>

<body>
    <div class="hero_area">
        <!-- header section strats -->
        @include('home.header')
    </div>
    <!-- end hero area -->

    <!-- Konten saldo pengguna -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="mt-5 card">
                    <div class="card-header">
                        <h3>Saldo Saya</h3>
                    </div>
                    <div class="card-body">
                        <!-- Menggunakan variabel $balance langsung -->
                        <p><strong>Rp {{ number_format($balance, 0, ',', '.') }}</strong></p>
                        <!-- Button to open the modal -->
                        <button id="openModalButton" class="btn btn-primary">Top Up</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- info section -->
    @include('home.footer')

    <!-- Modal for Midtrans payment form -->
    <div id="midtransModal" class="modal">
        <div class="modal-content">
            <form id="payment-form" method="post" action="">
                @csrf
                <input type="hidden" name="snap_token" id="snap_token">
                <label for="amount">Total Top Up </label>
                <input type="number" id="amount" name="amount" required>
                <button id="pay-button" class="btn btn-success">Bayar</button>
            </form>
            <script type="text/javascript">
                document.getElementById('pay-button').onclick = function(event) {
                    event.preventDefault();
                    const amount = document.getElementById('amount').value;

                    fetch('/transactions/create', {
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
                        .then(data => {
                            snap.pay(data.snap_token, {
                                onSuccess: function(result) {
                                    // Make another fetch request to hit the webhook endpoint
                                    fetch('/webhook/midtrans', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                // Add CSRF token if needed
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            // Include any data you want to send with the request
                                            body: JSON.stringify({
                                                // Add any additional data you want to send
                                                amount: amount
                                            })
                                        })
                                        .then(response => response.json())
                                        .then(responseData => {
                                            // Handle the response as needed
                                            console.log(responseData);
                                            window.location.href = '/balance';
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                        });
                                    window
                                },
                                onPending: function(result) {
                                    // Handle pending payment
                                },
                                onError: function(result) {
                                    console.error(result);
                                }
                            });
                        });

                };
            </script>
        </div>
    </div>

    <script>
        // Function to open the modal
        document.getElementById("openModalButton").addEventListener("click", function(event) {
            event.preventDefault();
            openModal();
        });

        // Function to open the modal
        function openModal() {
            var modal = document.getElementById("midtransModal");
            modal.style.display = "block";
        }

        // Function to close the modal
        window.onclick = function(event) {
            var modal = document.getElementById("midtransModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    </script>
</body>

</html>
