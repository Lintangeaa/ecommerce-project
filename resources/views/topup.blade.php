<form id="payment-form" method="post" action="">
    @csrf
    <input type="hidden" name="snap_token" id="snap_token">
    <label for="amount">Total Top Up</label>
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
                        fetch('/webhook/midtrans', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    // Add CSRF token if needed
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    // Add any additional data you want to send
                                    amount: amount
                                })
                            })
                            .then(response => response.json())
                            .then(responseData => {
                                console.log(responseData);
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
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
