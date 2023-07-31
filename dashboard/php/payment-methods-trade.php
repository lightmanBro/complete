<?php

// .
session_start();
$serverName = 'localhost';
$username = 'root';
$password = '';
$databaseName = 'register';
$conn = new mysqli($serverName,$username,$password,$databaseName);

// Include the configuration file that contains database credentials

$Paymentmethod = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = clean_Input($_POST['method']);
    $sellerId = clean_Input($_POST['sellerId']);

    // Validate and sanitize user input
    if (!in_array($method, ['Apple', 'bank', 'Google pay', 'paypal', 'Skrill', 'Pi', 'momo'])) {
        // Invalid method, handle the error appropriately
        echo json_encode(['error' => 'Invalid payment method']);
        exit;
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM $method WHERE user_id = ?");
    $stmt->bind_param('s', $sellerId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $Paymentmethod[$method] = $row;
        }
    }

    $stmt->close();
    $conn->close();

    echo json_encode($Paymentmethod);
}

// Function to sanitize user input
function clean_Input($userInput)
{
    $userInput = trim($userInput);
    $userInput = strip_tags($userInput);
    $userInput = stripslashes($userInput);
    $userInput = htmlspecialchars($userInput);
    return $userInput;
}
?>