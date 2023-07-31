<?php
session_start();
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

include '../server.php';

// Fetch users from the database
$usersQuery = "SELECT * FROM users";
$query = $pdo->query("SELECT * FROM admin WHERE email='{$_SESSION['SESSION_EMAIL']}'");

if ($query->rowCount() > 0) {
    $fetch = $query->fetch(PDO::FETCH_ASSOC);
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the users query to include the search condition
if (!empty($search)) {
    $usersQuery = "SELECT * FROM users WHERE first_name LIKE '%$search%' OR middle_name LIKE '%$search%' OR last_name LIKE '%$search%'";
} else {
    $usersQuery = "SELECT * FROM users";
}

$usersResult = $pdo->query($usersQuery);

// Update user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['editId'];
    $firstName = $_POST['editName'];
    $middleName = $_POST['editName'];
    $lastName = $_POST['editName'];
    $username = $_POST['editUsername'];
    $level = $_POST['editLevel'];
    $kycStatus = $_POST['editKycStatus'];
    $address = $_POST['editAddress'];

    $updateQuery = "UPDATE users SET first_name='$firstName', middle_name='$middleName', last_name='$lastName', username='$username', level='$level', kyc_status='$kycStatus', address='$address' WHERE id='$id'";

    if ($pdo->exec($updateQuery)) {
        echo "User details updated successfully.";
        header("Location: users.php");
        exit();
    } else {
        echo "Error updating user details.";
    }
}
?>
