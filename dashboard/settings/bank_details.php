<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the query to fetch bank details
    $query = "SELECT * FROM NigeriaBankDetails";
    $statement = $conn->prepare($query);

    // Execute the query
    $statement->execute();

    // Fetch all the rows returned by the query
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Iterate through the rows and process bank details
    foreach ($rows as $row) {
        $id = $row['ID'];
        $account_number = $row['account_number'];
        $account_name = $row['account_name'];
        $bank_name = $row['bank_name'];
        $branch_name = $row['branch_name'];
        $account_type = $row['account_type'];
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>
