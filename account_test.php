<?php
session_start();

// Check if the user is logged in and session data is available
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Include the database connection file
require_once 'server.php';

// Define an empty message variable
$msg = "";

// Process the BVN verification form submission
if (isset($_POST['submit'])) {
    // Get the form data
    $firstName = $_POST['first-name'];
    $middleName = $_POST['middle-name'];
    $lastName = $_POST['last-name'];
    $email = $_POST['email'];
    $bvn = $_POST['bvn'];

    // Include the Flutterwave config file and set the necessary parameters
    include 'config.php';

    // Set the necessary parameters for Flutterwave API
    $apiKey = isset($apiKey) ? $apiKey : '';
    $baseUrl = 'https://api.flutterwave.com/v3';
    $customPrefix = 'Tamopei - '; // Customize the prefix here

    // Combine the user's name for the narration
    $narration = $customPrefix . $firstName . ' ' . $middleName . ' ' . $lastName;

    // Set the request headers
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    );

    // Set the request data for NGN virtual account
    $data = array(
        'email' => $email,
        'amount' => 1, // Set the initial balance to zero
        'bvn' => $bvn,
        'narration' => $narration,
        'bank_code' => '035', // Wema Bank
    );

    // Make the API call
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/virtual-account-numbers');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    // Handle the API response
    $responseData = json_decode($response, true);

    if ($responseData && isset($responseData['status']) && $responseData['status'] === 'success') {
        // Virtual account created successfully

        // Get the virtual account number and bank name
        $accountNumber = $responseData['data']['account_number'];
        $bankName = $responseData['data']['bank_name'];

        // Display the response message with bank name and account number
        $responseMsg = 'Account created successfully.';
        $msg = $responseMsg . '<br>';
        $msg .= 'Bank Name: ' . $bankName . '<br>';
        $msg .= 'Account Number: ' . $accountNumber . '<br>';
    } else {
        // Error occurred while creating the virtual account
        $errorMessage = isset($responseData['message']) ? $responseData['message'] : 'An error occurred.';
        $msg = 'An error occurred: ' . $errorMessage . '<br>';

        // Display the response message
        $responseMsg = isset($responseData['message']) ? $responseData['message'] : 'An error occurred.';
        $msg .= 'Response: ' . $responseMsg . '<br>';
    }
}
?>

<!-- Rest of the HTML code remains the same -->

<!DOCTYPE html>
<html>
<head>
    <title>Complete Registration</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .btn {
            width: 100%;
            padding: 10px;
            font-size: 20px;
        }
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
<div class="form-container">
    <form action="" method="post">
        <h3>Complete Registration</h3>
        <?php echo $msg; ?>
        <input type="text" name="first-name" placeholder="First Name" class="box" required>
        <input type="text" name="middle-name" placeholder="Middle Name" class="box">
        <input type="text" name="last-name" placeholder="Last Name" class="box" required>
        <input type="email" name="email" placeholder="Email" class="box" required>
        <input type="text" name="bvn" placeholder="BVN" class="box" required>
        <button name="submit" class="btn" type="submit">Complete Registration</button>
    </form>
</div>
</body>
</html>
