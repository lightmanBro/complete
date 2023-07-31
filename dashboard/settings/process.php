<?php
// Sanitize user input data
$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
$address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
$dob = filter_var($_POST['dob'], FILTER_SANITIZE_STRING);
$document_type = filter_var($_POST['document_type'], FILTER_SANITIZE_STRING);
$document_number = filter_var($_POST['document_number'], FILTER_SANITIZE_STRING);

// Process document upload
$upload_dir = 'uploads/kyc'; // Specify the directory to store uploaded documents
$document_upload = $_FILES['document_upload']['tmp_name'];
$document_name = $_FILES['document_upload']['name'];
$document_path = $upload_dir . $document_name;

if (move_uploaded_file($document_upload, $document_path)) {
    // Document uploaded successfully

    // Store user information in the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "login";

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL statement to insert user data
    $sql = "INSERT INTO kyc_verification (name, address, dob, document_type, document_number, document_path) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $address, $dob, $document_type, $document_number, $document_path);
    $stmt->execute();

    // Close the database connection
    $stmt->close();
    $conn->close();

    echo "KYC information submitted successfully. It will be processed by an admin.";
} else {
    // Failed to upload the document
    echo "Error uploading the document.";
}
?>
