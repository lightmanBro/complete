<?php
session_start();

if (!isset($_SESSION['SESSION_EMAIL'])) {
    die("User session not found. Please log in.");
}

// Retrieve the form data
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

// Connect to the database
$host = 'localhost';
$dbName = 'login';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Update the user's profile
// Assuming you have a 'users' table with fields: id, name, email, password
$sql = "UPDATE users SET name = :name, email = :email, password = :password WHERE email = :session_email";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':name', $name);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':password', $password);
$stmt->bindParam(':session_email', $_SESSION['SESSION_EMAIL']);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    echo "Profile updated successfully!";
} else {
    echo "An error occurred while updating the profile.";
}
?>