<?php
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

// Get the webhook payload sent by Flutterwave
$payload = json_decode(file_get_contents('php://input'), true);

// Check if the payload is not empty and contains the necessary data
if (!empty($payload) && isset($payload['data']['id']) && isset($payload['data']['status'])) {
  // Extract the transaction ID and status from the webhook payload
  $transactionId = $payload['data']['id'];
  $status = $payload['data']['status'];

  // Check the transfer status using the transaction ID
  $live_api_key = 'FLWSECK-36136fb8d6f94920dd1da885c19a3ddf-18972d84372vt-X'; // Your live Flutterwave API Key
  $transferStatus = checkTransferStatus($transactionId, $live_api_key);

  // Update the user interface based on the transfer status
  if ($transferStatus === 'success') {
    // Transfer was successful
    echo 'Transfer successful. Transaction ID: ' . $transactionId;

    // Update the user interface to show that the transfer was successful
    // You can also update the transaction status in your database or records
    // and notify the user via email or any other means.
  } else {
    // Transfer failed
    $reason = $payload['data']['complete_message'] ?? 'Unknown Reason';

    echo 'Transfer failed. Reason: ' . $reason;

    // Update the user interface to show that the transfer failed and the reason
    // You can also handle the failure accordingly, such as showing an error message
    // to the user or providing them with instructions on how to proceed.
  }
} else {
  echo 'Invalid webhook payload. Missing data.';
}
?>
