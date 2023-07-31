<?php
// Make sure to sanitize and validate user inputs before using them in your queries
//Connected to new_trade.js
include('server.php');

// Check if the user is logged in and retrieve necessary session data
// session_start();
// if (!isset($_SESSION['id'], $_SESSION['name'], $_SESSION['wallet_balance'])) {
//     // Handle session not being set or expired
//     header("HTTP/1.1 401 Unauthorized");
//     exit;
// }

$accountOwner = $_SESSION['id'];
$accountOwnerName = $_SESSION['first_name'];
$wallets = $_SESSION['wallet_balance'];
print_r($wallets);
print_r($_SESSION['user_id']);
print_r($accountOwnerName."   ".$accountOwner);
header("Content-Type: application/json");
$response = array("Successful" => "", "Failed" => "");
$posted = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected = mysqli_real_escape_string($conn, $_POST['wallet']);
    $lowLimit = mysqli_real_escape_string($conn, $_POST['low_limit']);
    $highLimit = mysqli_real_escape_string($conn, $_POST['high_limit']);
    $Rate = mysqli_real_escape_string($conn, $_POST['rate']);
    $paymentMethod = mysqli_real_escape_string($conn, $_POST['method']);

    if ($_POST['type'] === 'buy') {
        // Prepare the query using prepared statements for better security
        $buy = "INSERT INTO p2p_posts_buy (`user_id`, `user_name`, `wallet`, `lowest_rate`, `highest_rate`, `user_rate`, `payment_method`)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $buyStmt = mysqli_prepare($conn, $buy);
        mysqli_stmt_bind_param($buyStmt, "issssss", $accountOwner, $accountOwnerName, $selected, $lowLimit, $highLimit, $Rate, $paymentMethod);
        $buyResult = mysqli_stmt_execute($buyStmt);

        if ($buyResult) {
            $posted['wallet']= $selected;
            $posted['low_limit']=$lowLimit;
            $posted['high_limit']=$highLimit;
            $posted['rate']=$Rate;
            $posted['payment_method']=$paymentMethod;
            $response["Successful"] = $posted;
        } else {
            // Handle the error case when the query execution fails
            $response['Failed'] = "Failed to post buy order";
        }

        mysqli_stmt_close($buyStmt);
    }

    if ($_POST['type'] === 'sell') {
        // Prepare the query using prepared statements for better security
        $sell = "INSERT INTO p2p_posts_sell (`user_id`, `user_name`, `wallet`, `lowest_rate`, `highest_rate`, `user_rate`, `payment_method`)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $sellStmt = mysqli_prepare($conn, $sell);
        mysqli_stmt_bind_param($sellStmt, "issssss", $accountOwner, $accountOwnerName, $selected, $lowLimit, $highLimit, $Rate, $paymentMethod);
        $sellResult = mysqli_stmt_execute($sellStmt);

        if ($sellResult) {
            $posted['wallet']= $selected;
            $posted['low_limit']=$lowLimit;
            $posted['high_limit']=$highLimit;
            $posted['rate']=$Rate;
            $posted['payment_method']=$paymentMethod;
            $response["Successful"] = $posted;
        } else {
            // Handle the error case when the query execution fails
            $response['Failed'] = "Failed to post sell order";
        }

        mysqli_stmt_close($sellStmt);
    }


    if ($_POST['type'] === 'buy_other') {
        $wallet_to = mysqli_real_escape_string($conn,$_POST['wallet_to']);
        // Prepare the query using prepared statements for better security
        $buy_other = "INSERT INTO p2p_post_buy_other_method (`user_id`, `user_name`, `wallet`, `lowest_rate`, `highest_rate`, `user_rate`,`wallet_to`, `payment_method`)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $buy_other_Stmt = mysqli_prepare($conn, $buy_other);
        mysqli_stmt_bind_param($buy_other_Stmt, "isssssss", $accountOwner, $accountOwnerName, $selected, $lowLimit, $highLimit, $Rate,$wallet_to, $paymentMethod);
        $buy_other_method = mysqli_stmt_execute($buy_other_Stmt);

        if ($buy_other_method) {
            $posted['wallet']= $selected;
            $posted['low_limit']=$lowLimit;
            $posted['high_limit']=$highLimit;
            $posted['rate']=$Rate;
            $posted['wallet_to'] = $wallet_to;
            $posted['payment_method']=$paymentMethod;
            $response["Successful"] = $posted;
        } else {
            // Handle the error case when the query execution fails
            $response['Failed'] = "Failed to post sell order";
        }

        mysqli_stmt_close($buy_other_Stmt);
    }

    if ($_POST['type'] === 'sell_other') {
        $wallet_to = mysqli_real_escape_string($conn,$_POST['wallet_to']);
        // Prepare the query using prepared statements for better security
        $sell_other = "INSERT INTO p2p_post_sell_other_method (`user_id`, `user_name`, `wallet`, `lowest_rate`, `highest_rate`, `user_rate`,`wallet_to`, `payment_method`)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $sell_other_Stmt = mysqli_prepare($conn, $sell_other);
        mysqli_stmt_bind_param($sell_other_Stmt, "isssssss", $accountOwner, $accountOwnerName, $selected, $lowLimit, $highLimit, $Rate,$wallet_to, $paymentMethod);
        $sell_other_method = mysqli_stmt_execute($sell_other_Stmt);

        if ($sell_other_method) {
            $posted['wallet']= $selected;
            $posted['low_limit']=$lowLimit;
            $posted['high_limit']=$highLimit;
            $posted['rate']=$Rate;
            $posted['wallet_to'] = $wallet_to;
            $posted['payment_method']=$paymentMethod;
            $response["Successful"] = $posted;
        } else {
            // Handle the error case when the query execution fails
            $response['Failed'] = "Failed to post sell order";
        }

        mysqli_stmt_close($sell_other_Stmt);
    }
}

echo json_encode($response);
