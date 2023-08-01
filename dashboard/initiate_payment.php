<?php
// Use your Flutterwave API keys here
$secret_key = 'FLWSECK_TEST-f398185cae03e254d43d47b5575057b5-X';

// Retrieve payment details from the form
$email = $_POST['email'];
$amount = $_POST['amount'];

// Make the payment request to Flutterwave
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.flutterwave.com/v3/charges?type=card",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
        'tx_ref' => time(),
        'amount' => $amount,
        'currency' => 'NGN', // Change to your desired currency code
        'redirect_url' => 'dashboard/fund_wallet.php/payment-successful',
        'payment_type' => 'card',
        'customer' => [
            'email' => $email,
        ],
    ]),
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $secret_key",
        "Content-Type: application/json",
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    // Handle payment error
    echo "Payment failed. Please try again later.";
} else {
    // Redirect the user to the payment gateway
    $response = json_decode($response, true);
    header("Location: " . $response['data']['link']);
    exit;
}
echo $response;
?>
