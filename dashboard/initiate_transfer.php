<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Include the database connection file
require_once '../server.php';

// Function to initiate the transfer using Flutterwave API
function initiateTransfer($bankCode, $accountNumber, $amount) {
    // Replace 'YOUR_FLUTTERWAVE_SECRET_KEY' with your Flutterwave secret key
    $secretKey = 'FLWSECK-be1c911f529ddfb74aad519c4c71b6ec-1895394b20fvt-X';

    // Prepare the transfer data
    $transferData = array(
        'account_bank' => $bankCode,
        'account_number' => $accountNumber,
        'amount' => $amount,
        'narration' => 'Transfer from Wallet',
        'currency' => 'NGN'
    );

    // Send a request to the Flutterwave API to initiate the transfer
    $ch = curl_init('https://api.flutterwave.com/v3/transfers');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($transferData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $secretKey
    ));

    $response = curl_exec($ch);
    curl_close($ch);

    // Handle the transfer response
    $responseData = json_decode($response, true);
    if (isset($responseData['status']) && $responseData['status'] === 'success') {
        echo 'Transfer successful.';
    } else {
        echo 'Transfer failed. Please try again.';
    }
}

// Retrieve the bank options from the database
$sql = "SELECT code, name FROM banks";
$stmt = $pdo->query($sql);
$banks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the selected bank, account number, and amount values from the form
    $bankCode = $_POST['bank'];
    $accountNumber = $_POST['accountNumber'];
    $amount = $_POST['amount'];

    // Call the initiateTransfer function with the bank code, account number, and amount
    initiateTransfer($bankCode, $accountNumber, $amount);
}
?>
