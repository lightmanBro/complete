<?php
include '../server.php';

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Fetch user details from the database based on the provided ID
    $userQuery = "SELECT * FROM users WHERE id = :userId";
    $stmt = $conn->prepare($userQuery);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Prepare user details as an associative array
        $userDetails = array(
            'id' => $user['id'],
            'first_name' => $user['first_name'],
            'middle_name' => $user['middle_name'],
            'last_name' => $user['last_name'],
            'username' => $user['username'],
            'level' => $user['level'],
            'email' => $user['email'],
            'kyc_status' => $user['kyc_status'],
            'address' => $user['address']
        );

        // Return user details as JSON
        header('Content-Type: application/json');
        echo json_encode($userDetails);
        exit;
    }
}

// Return empty response if user details are not found or an error occurs
echo json_encode(null);
?>
