<?php
// Replace 'your_paystack_secret_key_here' with your actual Paystack API key
$secretKey = 'sk_live_e6b5d21810aece3611b35816d89376024cac2212';

// Bank account details to verify
$accountNumber = "2374511883";
$bankCode = "057";

// API endpoint for account verification
$verifyAccountUrl = "https://api.paystack.co/bank/resolve?account_number=$accountNumber&bank_code=$bankCode";

// Set up the cURL request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $verifyAccountUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $secretKey",
]);

// Execute the cURL request
$response = curl_exec($ch);
curl_close($ch);

// Parse the JSON response
$verificationResult = json_decode($response, true);

// Display the account name
if ($verificationResult['status'] === true) {
    $accountName = $verificationResult['data']['account_name'];
    echo "Account Name: " . $accountName;
} else {
    echo "Account verification failed.";
}
