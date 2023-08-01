<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

// Get the transaction data from the query parameters
if (isset($_GET['tx_ref']) && isset($_GET['amount']) && isset($_GET['email'])) {
    $txRef = $_GET['tx_ref'];
    $amount = $_GET['amount'];
    $email = $_GET['email'];

    // Include the database connection file (if needed)
    // require_once '../server.php';

    // Perform any necessary validation or processing with the transaction data here
    // For example, you might want to check if the transaction reference is unique and if the amount is valid, etc.

    // Continue with the payment process
    // You can use the Flutterwave payment gateway integration library to initiate the checkout
    // Make sure to replace 'YOUR_FLUTTERWAVE_PUBLIC_KEY' with your actual Flutterwave public key

    // Example code to initiate Flutterwave checkout
    // Include the Flutterwave JavaScript library (if not already included in index.php)
    echo '<script src="https://checkout.flutterwave.com/v3.js"></script>';

    Output the JavaScript code to initiate the Flutterwave checkout
    echo '<script>';
    echo 'FlutterwaveCheckout({';
    echo 'public_key: "YOUR_FLUTTERWAVE_PUBLIC_KEY",';
    echo 'tx_ref: "' . $txRef . '",';
    echo 'amount: ' . $amount . ',';
    echo 'currency: "NGN",';
    echo 'payment_type: "card",';
    echo 'redirect_url: "fund_niara_response.php",'; // Send the transaction data to the response handling page
    echo 'callback: function (response) {';
    echo 'console.log(response);';
    echo 'if (response.status === "successful") {';
    echo 'alert("Payment was successful!");';
    echo '} else {';
    echo 'alert("Payment failed!");';
    echo '}';
    echo '},';
    echo '});';
    echo '</script>';

    // Note: Since I don't have access to your actual Flutterwave account, I've commented out the Flutterwave checkout code.
}
?>