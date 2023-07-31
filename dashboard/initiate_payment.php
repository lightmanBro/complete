<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Retrieve the selected currency and amount from the query parameters
$currency = $_GET['currency'];
$amount = $_GET['amount'];

// Function to initiate the payment using Flutterwave API
function initiatePayment($amount, $currency) {
  // Replace 'YOUR_API_KEY' with your Flutterwave API key
  $apiKey = 'FLWPUBK_TEST-1ff6e12ef890d663a80c73ec8f0fce26-X';

  // Prepare the payment data
  $paymentData = array(
    'tx_ref' => generateTransactionRef(),
    'amount' => $amount,
    'currency' => $currency,
    'redirect_url' => 'http://localhost/wallet/payment_confirmation.php',
    'meta' => array(
      'vendor_id' => $_SESSION['user_id']
    ),
    'customer' => array(
      'email' => $_SESSION['email']
    ),
    'customizations' => array(
      'title' => 'Add Funds to Wallet',
      'description' => 'Adding funds to your vendor wallet'
    )
  );

  // Store the payment data in the session
  $_SESSION['paymentData'] = $paymentData;

  // Redirect to your own checkout form to collect card details
  header('Location: checkout_form.php');
  exit;
}

// Function to generate a transaction reference
function generateTransactionRef() {
  return 'TXREF-' . uniqid();
}

// Call the initiatePayment function with the amount and currency
initiatePayment($amount, $currency);
