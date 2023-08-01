<?php
session_start();

if (!isset($_SESSION['email'])) {
    // Return an error response if the user is not logged in
    http_response_code(403);
    echo "Please log in to submit the form.";
    exit();
}

// Include the server.php file to get the database connection
include '../../server.php';

// Initialize the success message variable
$successMessage = '';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required fields are set and not empty
    if (isset($_POST['user_name']) && !empty($_POST['user_name'])) {
        // Sanitize and prepare the data for database insertion
        $user_name = $_POST['user_name'];

        // You need to get the user_id from the session or any other way you track the user's identity
        // In this example, I'll assume you have a user_id stored in the $_SESSION['user_id'] variable
        $user_id = $_SESSION['user_id'];

        try {
            // Prepare and execute the SQL query to insert the data into the database
            $stmt = $pdo->prepare("INSERT INTO chipper (user_id, user_name) VALUES (:user_id, :user_name)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':user_name', $user_name);

            if ($stmt->execute()) {
                $successMessage = "Chipper Cash details added successfully!";
                // Return the success message to the AJAX request
                echo $successMessage;
            } else {
                // Return an error response
                http_response_code(500);
                echo "Error: Unable to execute the query.";
            }
        } catch (PDOException $e) {
            // Return an error response
            http_response_code(500);
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Return an error response if form data is invalid
        http_response_code(400);
        echo "Please fill in all required fields!";
    }
}
?>
