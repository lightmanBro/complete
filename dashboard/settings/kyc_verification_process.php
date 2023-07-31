<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}
include '../../server.php';

// Retrieve user details
$query = $pdo->prepare("SELECT * FROM user_credentials WHERE email=:email");
$query->bindValue(':email', $_SESSION['email']);
$query->execute();

if ($query->rowCount() > 0) {
    $fetch = $query->fetch(PDO::FETCH_ASSOC);
}

// Process KYC verification form submission
if (isset($_POST['submit'])) {
    $document_type = $_POST['document_type'];
    $document_number = $_POST['document_number'];

    // Handle file uploads
    $target_dir_front_user = "uploads/kyc/front/";
    $target_dir_back_user = "uploads/kyc/back/";
    $target_dir_front_admin = "../../admin/uploads/kyc/front/";
    $target_dir_back_admin = "../../admin/uploads/kyc/back/";
    $front_id_image = $target_dir_front_user . basename($_FILES["front_id_image"]["name"]);
    $back_id_image = $target_dir_back_user . basename($_FILES["back_id_image"]["name"]);
    $front_id_image_admin = $target_dir_front_admin . basename($_FILES["front_id_image"]["name"]);
    $back_id_image_admin = $target_dir_back_admin . basename($_FILES["back_id_image"]["name"]);
    move_uploaded_file($_FILES["front_id_image"]["tmp_name"], $front_id_image);
    move_uploaded_file($_FILES["back_id_image"]["tmp_name"], $back_id_image);
    copy($front_id_image, $front_id_image_admin);
    copy($back_id_image, $back_id_image_admin);

    // Save KYC verification details to the database
    $insertQuery = $pdo->prepare("INSERT INTO kyc_verification (user_id, document_type, document_number, front_id_image, back_id_image, status) 
              VALUES (:user_id, :document_type, :document_number, :front_id_image, :back_id_image, 'pending')");
    $insertQuery->bindValue(':user_id', $fetch['user_id']);
    $insertQuery->bindValue(':document_type', $document_type);
    $insertQuery->bindValue(':document_number', $document_number);
    $insertQuery->bindValue(':front_id_image', $front_id_image);
    $insertQuery->bindValue(':back_id_image', $back_id_image);
    $insertQuery->execute();

    // Update KYC verification status for the user
    $kycStatus = 'pending';
    $updateQuery = $pdo->prepare("UPDATE user_credentials SET kyc_status=:kyc_status WHERE user_id=:user_id");
    $updateQuery->bindValue(':kyc_status', $kycStatus);
    $updateQuery->bindValue(':user_id', $fetch['user_id']);
    $updateQuery->execute();

    // Set the kyc_submitted session variable
    $_SESSION['kyc_submitted'] = true;

    // Redirect to a success page
    header("Location: index.php");
    exit();
}
?>
