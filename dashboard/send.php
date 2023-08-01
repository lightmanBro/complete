<!DOCTYPE html>
<html>
<head>
    <title>Payment Form</title>
</head>
<body>

<h1>Payment Form</h1>

<!-- Add your other form fields here as needed -->
<!-- For example, you might collect customer details, order information, etc. -->

<!-- Payment Button -->
<button onclick="initiatePayment()">Pay Now</button>

<!-- JavaScript Code -->
<script>
  // Function to initiate the payment request
  function initiatePayment() {
    // Your Flutterwave API key
    const api_key = 'FLWPUBK-9833fff9d279b3ce1c3bb79f885248d6-X';

    // API endpoint for creating a mobile money Ghana payment
    const endpoint = 'https://api.flutterwave.com/v3/charges?type=mobile_money_ghana';

    // Transaction reference, should be unique for each transaction
    const tx_ref = Date.now().toString();

    // Payment data
    const paymentData = {
      tx_ref: tx_ref,
      amount: 100, // Amount in the currency's smallest unit (e.g., cents).
      currency: 'GHS', // Currency code for Ghanaian Cedi.
      payment_type: 'mobilemoneygh', // Payment type for Ghanaian mobile money.
      redirect_url: 'https://yourwebsite.com/callback',
      // Add other required parameters for mobile money or bank transfer collection.
    };

    // Make a POST request to Flutterwave API using XMLHttpRequest
    const xhr = new XMLHttpRequest();
    xhr.open('POST', endpoint, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('Authorization', `Bearer ${api_key}`);

    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          console.log('Payment request successful:');
          console.log(JSON.parse(xhr.responseText));
          // Redirect the user to the payment URL returned by Flutterwave
          window.location.href = JSON.parse(xhr.responseText).data.link;
        } else {
          console.error('Payment request failed:', JSON.parse(xhr.responseText));
        }
      }
    };

    xhr.send(JSON.stringify(paymentData));
  }
</script>

</body>
</html>
