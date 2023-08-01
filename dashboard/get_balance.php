<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

// Include the database connection file
require_once '../server.php';

// Get the selected currency from the AJAX request
$selectedCurrency = $_GET['currency'];

// Retrieve the user data from the database
$sql = "SELECT $selectedCurrency FROM wallet WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();

// Check if the currency balance was found
if ($stmt->rowCount() == 1) {
    $balance = $stmt->fetchColumn();
    // Return the balance as a JSON response
    echo json_encode(['balance' => $balance]);
} else {
    // Currency balance not found
    echo json_encode(['error' => 'Balance not found']);
}
?>
