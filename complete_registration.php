<?php
session_start();

// Check if the user is logged in and session data is available
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Get the email from the session
$email = $_SESSION['email'];

// Include the database connection file
require_once 'server.php';

// Retrieve the user data from the database using the email
$sql = "SELECT * FROM user_credentials WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();

// Check if user exists
if ($stmt->rowCount() == 1) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user has already provided BVN
    if (!empty($user['bvn_hash'])) {
        header("Location: dashboard/index.php");
        exit();
    }

    // Define an empty message variable
    $msg = "";

    // Process the BVN verification form submission
    if (isset($_POST['submit'])) {
        // Get the BVN from the form data
        $bvn = $_POST['bvn'];

        // Include the Flutterwave config file and set the necessary parameters
        include 'config.php';

        // Set the necessary parameters for Flutterwave API
        $apiKey = isset($apiKey) ? $apiKey : '';
        $baseUrl = 'https://api.flutterwave.com/v3';
        $customPrefix = 'Tamopei - '; // Customize the prefix here

        // Combine the user's name for the narration
        $narration = $customPrefix . $user['first_name'] . ' ' . $user['last_name'];

        // Set the request headers
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        );

        // Set the request data for NGN virtual account
        $data = array(
            'email' => $email,
            'amount' => 0, // Set the initial balance to zero
            'bvn' => $bvn,
            'narration' => $narration,
            'is_permanent' => true,
            // Add other required parameters if needed
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

            // Get the virtual account number
            $accountNumber = $responseData['data']['account_number'];

            // Update the user's BVN hash and account number in the database
            $bvnHash = password_hash($bvn, PASSWORD_DEFAULT);
            $sql = "UPDATE user_credentials SET bvn_hash = :bvnHash, account_number = :accountNumber WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':bvnHash', $bvnHash);
            $stmt->bindParam(':accountNumber', $accountNumber);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Redirect to the dashboard or any other page after successful BVN verification
            header("Location: dashboard/index.php");
            exit();
        } else {
            // Error occurred while creating the virtual account
            $errorMessage = isset($responseData['message']) ? $responseData['message'] : 'An error occurred.';
            $msg = 'An error occurred: ' . $errorMessage;
        }
    }
} else {
    // User not found
    header("Location: index.php");
    exit();
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
        <input type="text" name="first-name" value="<?php echo $user['first_name']; ?>" placeholder="First Name" class="box" disabled required>
        <input type="text" name="middle-name" value="<?php echo $user['middle_name']; ?>" placeholder="Middle Name" class="box" disabled>
        <input type="text" name="last-name" value="<?php echo $user['last_name']; ?>" placeholder="Last Name" class="box" disabled>
        <input type="email" name="email" value="<?php echo $email; ?>" placeholder="Email" class="box" disabled required>
        <input type="text" name="bvn" placeholder="BVN" class="box" required>
        <button name="submit" class="btn" type="submit">Complete Registration</button>
    </form>
</div>
</body>
</html>
