<?php
session_start();
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

include '../server.php';

if (isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    // Delete the user from the users table
    $deleteUserSql = "DELETE FROM users WHERE id=:userId";
    $deleteUserStmt = $pdo->prepare($deleteUserSql);
    $deleteUserStmt->bindParam(':userId', $userId);
    $deleteUserResult = $deleteUserStmt->execute();

    // Delete the user's submitted documents from the kyc_verification table
    $deleteDocumentsSql = "DELETE FROM kyc_verification WHERE user_id=:userId";
    $deleteDocumentsStmt = $pdo->prepare($deleteDocumentsSql);
    $deleteDocumentsStmt->bindParam(':userId', $userId);
    $deleteDocumentsResult = $deleteDocumentsStmt->execute();

    // Update the KYC status to "Not Submitted" in the users table
    $updateKYCStatusSql = "UPDATE users SET kyc_status = 'Not Submitted' WHERE id = :userId";
    $updateKYCStatusStmt = $pdo->prepare($updateKYCStatusSql);
    $updateKYCStatusStmt->bindParam(':userId', $userId);
    $updateKYCStatusResult = $updateKYCStatusStmt->execute();

    // Check if the deletion was successful
    if ($deleteUserResult && $deleteDocumentsResult && $updateKYCStatusResult) {
        $response = array('success' => true);
    } else {
        $response = array('success' => false);
    }

    // Send the response back to the client
    echo json_encode($response);
}
?>
