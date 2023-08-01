<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Replace 'YOUR_PUBLIC_KEY' and 'YOUR_SECRET_KEY' with your actual Flutterwave API credentials
    $publicKey = "FLWPUBK-5a5793248416bc75092d47928552f417-X";
    $secretKey = "FLWSECK-36136fb8d6f94920dd1da885c19a3ddf-18972d84372vt-X";

    // Extract form data
    $amount = $_POST["amount"];
    $network = $_POST["network"];
    $mobileNumber = $_POST["mobile_number"];
    $accountName = $_POST["account_name"];
    $narration = $_POST["narration"];
    $email = $_POST["email"]; // New email field

    // Validate the data (you can add more validation as needed)
    if (empty($amount) || empty($network) || empty($mobileNumber) || empty($accountName) || empty($email)) {
        echo "All required fields must be filled.";
        exit;
    }

    // Create an array of data to send to the Flutterwave API
    $data = array(
        "tx_ref" => uniqid(), // Replace with a unique transaction reference
        "amount" => $amount,
        "currency" => "GHS",
        "payment_type" => "mobilemoneygh",
        "redirect_url" => "https://example.com/payment_success", // Replace with your desired success URL
        "network" => $network,
        "order_id" => uniqid(), // Replace with a unique order ID
        "phone_number" => $mobileNumber,
        "fullname" => $accountName,
        "narration" => $narration,
        "email" => $email, // Include the email address in the request
    );

    // Encode the data to JSON
    $jsonPayload = json_encode($data);

    // Set the API endpoint URL
    $url = "https://api.flutterwave.com/v3/charges?type=mobile_money_ghana";

    // Set up the cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: Bearer $secretKey",
        "Content-Type: application/json",
    ));

    // Execute the cURL request
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the response JSON
$responseData = json_decode($response, true);

// Print the API response for debugging
print_r($responseData);

    // Redirect the user to the Flutterwave payment page
    if (isset($responseData["data"]["link"])) {
        header("Location: " . $responseData["data"]["link"]);
        exit;
    } else {
        // Handle error response from the API
        echo "An error occurred. Please try again later.";
    }
}
?>
