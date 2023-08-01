<?php
// Your live Flutterwave API Key
$live_api_key = 'FLWSECK-36136fb8d6f94920dd1da885c19a3ddf-18972d84372vt-X';

// Function to check the transfer status using the transaction ID
function checkTransferStatus($transactionId, $live_api_key) {
  // API endpoint for fetching transfer status
  $endpoint = 'https://api.flutterwave.com/v3/transfers/' . $transactionId;

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $endpoint);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $live_api_key, // Use the live API key here
    'Content-Type: application/json'
  ));

  $response = curl_exec($ch);

  // Check if there was an error with the cURL request
  if (curl_errno($ch)) {
    return 'Failed to connect to Flutterwave API. Error: ' . curl_error($ch);
  }

  curl_close($ch);

  // Handle the API response
  $responseData = json_decode($response, true);

  // Check if the response data is valid JSON
  if ($responseData === null || !is_array($responseData)) {
    return 'Failed to parse the API response. Invalid JSON data.';
  }

  // Check if the API response contains a 'status' key
  if (isset($responseData['status'])) {
    if ($responseData['status'] === 'success') {
      // Transfer status retrieved successfully
      return $responseData['data']['status'];
    } else {
      // Failed to fetch transfer status
      return 'Failed to fetch transfer status. Error: ' . $responseData['message'];
    }
  } else {
    return 'Invalid API response. Missing "status" field.';
  }
}

// Retrieve form data
$amountInNaira = $_POST['amount']; // The amount input by the user in Naira

$bankCode = $_POST['bank'];
$accountNumber = $_POST['accountNumber'];

// Debug: Print the bank code to check if it's being passed correctly
echo 'Bank Code: ' . $bankCode;

// Prepare data for the Flutterwave API request
$data = array(
  'account_bank' => $bankCode,
  'account_number' => $accountNumber,
  'amount' => $amountInNaira, // Provide the amount directly in Naira
  'narration' => 'Transfer from My App', // Optional: Add a custom narration
  'currency' => 'NGN', // Assuming you are transferring in Nigerian Naira (NGN)
  'reference' => uniqid(), // Optional: Provide a unique reference for the transfer
  'callback_url' => 'http://localhost/complete/dashboard/webhook.php' // Replace with your actual webhook endpoint URL
);

// Send the request to Flutterwave API using cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/complete/transfers/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Authorization: Bearer ' . $live_api_key, // Use the live API key here
  'Content-Type: application/json'
));

$response = curl_exec($ch);

// Check if there was an error with the cURL request
if (curl_errno($ch)) {
  echo 'Failed to connect to Flutterwave API. Error: ' . curl_error($ch);
  exit;
}

curl_close($ch);

// Handle the API response
$responseData = json_decode($response, true);

// Check if the response data is valid JSON
if ($responseData === null || !is_array($responseData)) {
  echo 'Failed to parse the API response. Invalid JSON data.';
  exit;
}

// Check if the API response contains a 'status' key
if (isset($responseData['status'])) {
  if ($responseData['status'] === 'success') {
    // Transfer initiated successfully
    echo 'Transfer request has been queued for processing. Transaction ID: ' . $responseData['data']['id'] . '<br>';
    echo 'Amount Transferred: ' . $responseData['data']['amount'] . ' NGN<br>';
    echo 'Bank Name: ' . $responseData['data']['bank_name'] . '<br>';
    echo 'Account Number: ' . $responseData['data']['account_number'] . '<br>';
    echo 'Account Name: ' . $responseData['data']['full_name'] . '<br>';
    echo 'Status: ' . $responseData['data']['status'];

    // Provide the user with a unique reference number
    $referenceNumber = $responseData['data']['reference'];
    echo '<br><br>Reference Number: ' . $referenceNumber;
    echo '<br><br><strong>Instructions:</strong>';
    echo '<br>1. Please keep this reference number safe.';
    echo '<br>2. You will be notified of the transfer status through the webhook.';
    echo '<br>3. If you encounter any issues, please contact customer support for assistance.';
  } else {
    // Failed to initiate transfer
    echo 'Failed to initiate transfer. Error: ' . $responseData['message'];
  }
} else {
  echo 'Invalid API response. Missing "status" field.';
}
?>
