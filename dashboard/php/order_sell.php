<?php
//Sell Posts
include('server.php');
$accountOwner = $_SESSION['user_id'];
$wallets = $_SESSION['wallet_balance'];
$sellOrders = array();

// Fetch data from the 'p2p_posts_sell' table.
$stmt2 = "SELECT 
            `ind`,
            `user_id`,
            `user_name`,
            `wallet`,
            `lowest_rate`,
            `highest_rate`,
            `user_rate`,
            `payment_method`,
            `sell_order_refrence`,
            `date`
         FROM `p2p_posts_sell`
         ORDER BY `date` DESC";

$result = mysqli_query($conn, $stmt2);

// Check if there are rows in the result.
if ($result->num_rows > 0) {
    // Create an array to store the rows.
    $data = array();

    // Fetch each row and store it in the $data array.
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Assign the $data array to the 'wallet' key in the $sellOrders array.
    $sellOrders['wallet'] = $data;
}

// Fetch data from the 'p2p_post_sell_other_method' table.
$stmtOther = "SELECT 
                `ind`,
                `user_id`,
                `user_name`,
                `wallet`,
                `lowest_rate`,
                `highest_rate`,
                `user_rate`,
                `wallet_to`,
                `payment_method`,
                `sell_other_refrence`
             FROM `p2p_post_sell_other_method`
             ORDER BY `time` DESC";

$OtherResult = mysqli_query($conn, $stmtOther);

// Check if there are rows in the result.
if ($OtherResult->num_rows > 0) {
    // Create an array to store the rows.
    $data = array();

    // Fetch each row and store it in the $data array.
    while ($row = $OtherResult->fetch_assoc()) {
        $data[] = $row;
    }

    // Assign the $data array to the 'other' key in the $sellOrders array.
    $sellOrders['other_sell'] = $data;
}

// Convert the $sellOrders array to JSON format.
$jsonResponse = json_encode($sellOrders);

// Set the appropriate content-type header for JSON.
header('Content-Type: application/json');

// Echo the JSON response.
echo $jsonResponse;
// Note: No need to close the database connection here. PHP will automatically close it at the end of script execution.
?>
