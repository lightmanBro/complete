<!DOCTYPE html>
<html>
<head>
  <title>Payment Form</title>
  <script src="https://checkout.flutterwave.com/v3.js"></script>
</head>
<body>
<div class="naira-details" style="display: none;">
                <p>Fund your naira wallet through either Bank transfer or Card:</p>
                <button type="button" id="bank-transfer-btn">Bank Transfer</button>
                <button type="button" id="card-btn" data-selected="false">Card</button>

                <!-- Account details from the virtual account table (hidden by default) -->
                <div class="account-details" style="display: none;">
                    <h3>Bank Name: <span><?php echo $bankName; ?></span></h3>
                    <div class="account-number">
                        Account Number: <?php echo $accountNumber; ?>
                    </div>
                </div>
            </div>
  <form id="paymentForm" action="initiate_payment.php" method="POST">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    
    <label for="amount">Amount:</label>
    <input type="number" id="amount" name="amount" step="0.01" required>

    <button type="button" onclick="payWithFlutterwave()">Pay Now</button>
  </form>

  <script>
    function payWithFlutterwave() {
      const name = document.getElementById('name').value;
      const email = document.getElementById('email').value;
      const amount = document.getElementById('amount').value;

      FlutterwaveCheckout({
        public_key: 'FLWPUBK-9833fff9d279b3ce1c3bb79f885248d6-X',
        tx_ref: Date.now(),
        amount: amount,
        currency: 'NGN', // Change to your desired currency code
        payment_type: 'card',
        customer: {
          email: email,
          name: name,
        },
        callback: function(response) {
          console.log(response);
          if (response.status === 'successful') {
            document.getElementById('paymentForm').submit();
          } else {
            // Handle payment failure
            alert('Payment failed. Please try again.');
          }
        },
      });
    }
  </script>
</body>
</html>
