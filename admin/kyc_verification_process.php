<?php
session_start();
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

include '../server.php';

if (isset($_POST['action']) && isset($_POST['user_id'])) {
    $submissionId = $_POST['user_id'];
    $status = ($_POST['action'] === 'approve') ? 'Approved' : 'Rejected';

    // Fetch user ID from kyc_verification table
    $fetchUserSql = "SELECT user_id FROM kyc_verification WHERE ind=:submission_id";
    $fetchUserQuery = $pdo->prepare($fetchUserSql);
    $fetchUserQuery->bindValue(':submission_id', $submissionId);
    $fetchUserQuery->execute();
    $row = $fetchUserQuery->fetch(PDO::FETCH_ASSOC);
    $userId = $row['user_id'];

    // Update KYC status for the submission
    $updateKycSql = "UPDATE kyc_verification SET status=:status WHERE ind=:submission_id";
    $updateKycQuery = $pdo->prepare($updateKycSql);
    $updateKycQuery->bindValue(':status', $status);
    $updateKycQuery->bindValue(':submission_id', $submissionId);
    $updateKycQuery->execute();

    // Check if KYC is approved
    if ($status === 'Approved') {
        // Update KYC status in the user_credentials table
        $updateUserSql = "UPDATE user_credentials SET kyc_status=:status, level='Tier2' WHERE user_id=:user_id";
        $updateUserQuery = $pdo->prepare($updateUserSql);
        $updateUserQuery->bindValue(':status', $status);
        $updateUserQuery->bindValue(':user_id', $userId);
        $updateUserQuery->execute();
    } else {
        // KYC is rejected, no need to update the level
        $updateUserSql = "UPDATE user_credentials SET kyc_status=:status WHERE user_id=:user_id";
        $updateUserQuery = $pdo->prepare($updateUserSql);
        $updateUserQuery->bindValue(':status', $status);
        $updateUserQuery->bindValue(':user_id', $userId);
        $updateUserQuery->execute();
    }

    // Redirect back to the KYC Submissions page
    header("Location: kyc_submission.php");
    exit();
}
?>
