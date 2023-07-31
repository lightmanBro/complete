<?php
session_start();
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

include '../../config.php';

$msg = "";

if (isset($_POST['submit'])) {
    $bankName = mysqli_real_escape_string($conn, $_POST['bank_name']);
    $accountNumber = mysqli_real_escape_string($conn, $_POST['account_number']);
    $accountName = mysqli_real_escape_string($conn, $_POST['account_name']);

    // Check if the user already has bank details
    $existingBankDetails = mysqli_query($conn, "SELECT * FROM bank_details WHERE user_id = '$Id' LIMIT 1");
    if (mysqli_num_rows($existingBankDetails) > 0) {
        // Update existing bank details
        $updateQuery = "UPDATE bank_details SET bank_name = '$bankName', account_number = '$accountNumber', account_name = '$accountName' WHERE user_id = '$Id'";
        $result = mysqli_query($conn, $updateQuery);

        if ($result) {
            $msg = "<div class='alert alert-success'>Bank details updated successfully.</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Failed to update bank details.</div>";
        }
    } else {
        // Insert new bank details
        $insertQuery = "INSERT INTO bank_details (user_id, bank_name, account_number, account_name) VALUES ('$Id', '$bankName', '$accountNumber', '$accountName')";
        $result = mysqli_query($conn, $insertQuery);

        if ($result) {
            // Update the 'bank_details_added' field in the 'users' table
            $updateUserQuery = "UPDATE users SET bank_details_added = TRUE WHERE user_id = '$Id'";
            $updateUserResult = mysqli_query($conn, $updateUserQuery);

            if ($updateUserResult) {
                $msg = "<div class='alert alert-success'>Bank details saved successfully.</div>";
            } else {
                $msg = "<div class='alert alert-danger'>Failed to save bank details.</div>";
            }
        } else {
            $msg = "<div class='alert alert-danger'>Failed to save bank details.</div>";
        }
    }
}
?>
