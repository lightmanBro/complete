<?php
// Replace 'your_paystack_secret_key_here' with your actual Paystack API key
$secretKey = 'sk_live_e6b5d21810aece3611b35816d89376024cac2212';

// API endpoint for bank list
$bankListUrl = "https://api.paystack.co/bank";

// Set up the cURL request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $bankListUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $secretKey",
]);

// Execute the cURL request
$response = curl_exec($ch);
curl_close($ch);

// Parse the JSON response
$bankList = json_decode($response, true);

// Check if the bank list was successfully fetched
if ($bankList['status'] === true) {
    $bankOptions = $bankList['data'];
} else {
    $bankOptions = [];
}

// Return the bank options as JSON response
header('Content-Type: application/json');
echo json_encode(['bankOptions' => $bankOptions]);
