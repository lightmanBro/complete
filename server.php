<?php

// Database credentials
$host = 'localhost';
$db_name = 'register';
$db_username = 'root';
$db_password = 'password';

// Attempt to connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $db_username, $db_password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Error: ' . $e->getMessage());
}

// Rest of your code...
?>
