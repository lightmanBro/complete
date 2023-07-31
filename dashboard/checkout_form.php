<?php
session_start();

// Check if the payment data is stored in the session
if (!isset($_SESSION['paymentData'])) {
    header("Location: add_fund.php");
    exit();
}

// Retrieve the stored payment data from the session
$paymentData = $_SESSION['paymentData'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout Form</title>
</head>
<body>
    <h3>Checkout Form</h3>
    <p>Click the button below to proceed with the payment:</p>
    <button onclick="makePayment()">Make Payment</button>

    <!-- JavaScript code -->
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script>
        function makePayment() {
            FlutterwaveCheckout({
                public_key: 'FLWPUBK_TEST-1ff6e12ef890d663a80c73ec8f0fce26-X',
                tx_ref: '<?php echo $paymentData['tx_ref']; ?>',
                amount: <?php echo $paymentData['amount']; ?>,
                currency: '<?php echo $paymentData['currency']; ?>',
                redirect_url: '<?php echo $paymentData['redirect_url']; ?>',
                customer: {
                    email: '<?php echo $paymentData['customer']['email']; ?>'
                },
                customizations: {
                    title: '<?php echo $paymentData['customizations']['title']; ?>',
                    description: '<?php echo $paymentData['customizations']['description']; ?>'
                }
            });
        }
    </script>
</body>
</html>
