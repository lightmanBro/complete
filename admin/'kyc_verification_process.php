<?php
session_start();
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

include '../config.php';

// Handle KYC approval/rejection
if (isset($_POST['approve'])) {
    $id = $_POST['id'];

    // Update KYC status to "Approved"
    $updateSql = "UPDATE kyc_verification SET status='Approved' WHERE id='$id'";
    mysqli_query($conn, $updateSql);
}

if (isset($_POST['reject'])) {
    $id = $_POST['id'];

    // Update KYC status to "Rejected"
    $updateSql = "UPDATE kyc_verification SET status='Rejected' WHERE id='$id'";
    mysqli_query($conn, $updateSql);
}

// Fetch KYC submissions
$sql = "SELECT * FROM kyc_verification";
$result = mysqli_query($conn, $sql);

// Redirect back to the admin panel
header("Location: kyc_submission.php");
exit();
?>
