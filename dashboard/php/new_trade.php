<?php
//Where all the P2P trades are created;
//Connected to new_trade.js
/*
Post trade to the trade page
Rmove value posted from the user wallet and add it to the p2p order table;
*/
include('server.php');
$accountOwner = $_SESSION['user_id'];
$accountOwnerName = '';
$wallets = $_SESSION['wallet_balance'];

//Select the first name of this user from the table;
$sql = "SELECT `first_name` FROM .user_credentials WHERE user_id = $accountOwner";
$user_credentials = mysqli_query($conn, $sql);
if ($user_credentials) {
    $row = mysqli_fetch_assoc($user_credentials);
    $accountOwnerName = $row['first_name'];
}


$response = array("Successful" => "", "Failed" => "");
$posted = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected = mysqli_real_escape_string($conn, $_POST['wallet']);
    $lowLimit = mysqli_real_escape_string($conn, $_POST['low_limit']);
    $highLimit = mysqli_real_escape_string($conn, $_POST['high_limit']);
    $Rate = mysqli_real_escape_string($conn, $_POST['rate']);
    $paymentMethod = mysqli_real_escape_string($conn, $_POST['method']);

    //Check the balance against the balance inside the database
    $stmt = $conn->prepare("SELECT {$selected} FROM `wallet` WHERE user_id = ?");
        $stmt->bind_param('s', $_SESSION['user_id']);
        $stmt->execute();
        $stmt->bind_result($userBalance);
        $stmt->fetch();
        $stmt->close();

    function removeFee($select,$highLimit){
        $fee = $highLimit * 0.008;
        //Based on the currency posted for p2p, remove the post fee;
        if($select == 'Dollar' && $highLimit <100) $fee = 0.5;
        if($select == 'Dollar' && $highLimit >= 100) $fee = 1;
        return $fee;
    };


    if ($_POST['type'] === 'buy') {

        //Function to check the currency type and remove the appropiate fee
        $p2pFee = removeFee($selected,$highLimit);
        //To remove the amount + p2p fee from the user wallet;
        $amountAndFee = $highLimit + $p2pFee;

        if ($selected !== $paymentMethod && $selected != '' && $paymentMethod != '' && $userBalance >= $amountAndFee) {
            
            $refrence = substr(md5(uniqid(rand(), true)), 0, 20);
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM p2p_posts_sell WHERE sell_order_refrence = ?");
            $stmt->bind_param('s', $refrence);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
            
            if ($count > 0) {
                $refrence = substr(md5(uniqid(rand(), true)), 0, 20);
            }
            
            // Prepare the query using prepared statements for better security
            $buy = "INSERT INTO p2p_posts_sell (`user_id`, `user_name`, `wallet`, `lowest_rate`, `highest_rate`, `user_rate`, `payment_method`,`sell_order_refrence`)
                    VALUES (?, ?, ?, ?, ?, ?, ?,?)";
            $buyStmt = mysqli_prepare($conn, $buy);
            //Post highLimit only and send the p2p fee to p2p fee table;
            mysqli_stmt_bind_param($buyStmt, "isssssss", $accountOwner, $accountOwnerName, $selected, $lowLimit, $highLimit, $Rate, $paymentMethod, $refrence);
            $buyResult = mysqli_stmt_execute($buyStmt);

            $p2pFeeUpdate = $conn->prepare("UPDATE `p2p_fee` SET {$selected} = {$selected} + ?");
            $p2pFeeUpdate->bind_param('d',$p2pFee);
            $p2pfeeUpdated = $p2pFeeUpdate->execute();
            $p2pFeeUpdate->close();

            //Remove the value from the user wallet because it is already inside p2p
            $stmt = $conn->prepare("UPDATE wallet SET {$selected} = {$selected} - ? WHERE `user_id` = ?");
            $stmt->bind_param('ds', $amountAndFee, $accountOwner);
            $removed = $stmt->execute();
            $stmt->close();


            if ($buyResult) {
                $posted['wallet'] = $selected;
                $posted['low_limit'] = $lowLimit;
                $posted['high_limit'] = $highLimit;
                $posted['rate'] = $Rate;
                $posted['payment_method'] = $paymentMethod;
                $posted['total'] = $amountAndFee;
                $posted['fee'] = $p2pFee;
                $response["Successful"] = $posted;
            } else {
                // Handle the error case when the query execution fails
                $response['Failed'] = "Failed to post buy order";
            }
            mysqli_stmt_close($buyStmt);
        }elseif ($selected != '' && $paymentMethod != '') {
            $response['Empty'] = "One of Wallet-from or wallet-to canot be empty";
        }elseif ($userBalance < $highLimit) {
            $response['Insufficient'] = "Insufficient wallet balance";
        } else {
            $response['Same'] = "Wallet-from and wallet-to can not be the same";
        }
        //Creating a refrence id

    }

    if ($_POST['type'] === 'sell') {
        //Function to check the currency type and remove the appropiate fee
        $p2pFee = removeFee($selected,$highLimit);
        //To remove the amount + p2p fee from the user wallet;
        $amountAndFee = $highLimit + $p2pFee;


        if ($selected !== $paymentMethod && $selected != '' && $paymentMethod != '' && $userBalance >= $amountAndFee) {
            //Generate unique ref
            $refrence = substr(md5(uniqid(rand(), true)), 0, 20);
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM p2p_posts_buy WHERE buy_order_refrence = ?");
            $stmt->bind_param('s', $refrence);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                $refrence = substr(md5(uniqid(rand(), true)), 0, 20);
            }
            // Prepare the query using prepared statements for better security
            $sell = "INSERT INTO p2p_posts_buy (`user_id`, `user_name`, `wallet`, `lowest_rate`, `highest_rate`, `user_rate`, `payment_method`,`buy_order_refrence`)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $sellStmt = mysqli_prepare($conn, $sell);
            mysqli_stmt_bind_param($sellStmt, "isssssss", $accountOwner, $accountOwnerName, $selected, $lowLimit, $highLimit, $Rate, $paymentMethod, $refrence);
            $sellResult = mysqli_stmt_execute($sellStmt);

            $p2pFeeUpdate = $conn->prepare("UPDATE `p2p_fee` SET {$selected} = {$selected} + ?");
            $p2pFeeUpdate->bind_param('d',$p2pFee);
            $p2pfeeUpdated = $p2pFeeUpdate->execute();
            $p2pFeeUpdate->close();

            //Remove the value from the user wallet because it is already inside p2p
            $stmt = $conn->prepare("UPDATE wallet SET {$selected} = {$selected} - ? WHERE `user_id` = ?");
            $stmt->bind_param('ds', $highLimit, $accountOwner);
            $removed = $stmt->execute();
            $stmt->close();

            if ($sellResult) {
                $posted['wallet'] = $selected;
                $posted['low_limit'] = $lowLimit;
                $posted['high_limit'] = $highLimit;
                $posted['rate'] = $Rate;
                $posted['fee'] = $p2pFee;
                $posted['payment_method'] = $paymentMethod;
                $response["Successful"] = $posted;
            } else {
                // Handle the error case when the query execution fails
                $response['Failed'] = "Failed to post sell order";
            }
            mysqli_stmt_close($sellStmt);
        }elseif ($selected != '' && $paymentMethod != '') {
            $response['Empty'] = "One of Wallet-from or wallet-to canot be empty";
        }elseif ($userBalance < $highLimit) {
            $response['Insufficient'] = "Insufficient wallet balance";
        } else {
            $response['Same'] = "Wallet-from and wallet-to canot be thesame";
        }
    }

    //////////////////////////////////////////////////BUY WALLET OTHER METHOD////////////////////////////////////////////

    if ($_POST['type'] === 'buy_other') {
        
        $wallet_to = mysqli_real_escape_string($conn, $_POST['wallet_to']);

        if ($selected !== $wallet_to && $selected != '' && $wallet_to != '' && $userBalance >= $highLimit) {
            $refrence = substr(md5(uniqid(rand(), true)), 0, 20);
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM p2p_post_sell_other_method WHERE sell_other_refrence = ?");
            $stmt->bind_param('s', $refrence);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                $refrence = substr(md5(uniqid(rand(), true)), 0, 20);
            }
            // Prepare the query using prepared statements for better security
            $buy_other = "INSERT INTO p2p_post_sell_other_method (`user_id`, `user_name`, `wallet`, `lowest_rate`, `highest_rate`, `user_rate`,`wallet_to`, `payment_method`, `sell_other_refrence`)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $buy_other_Stmt = mysqli_prepare($conn, $buy_other);
            mysqli_stmt_bind_param($buy_other_Stmt, "issssssss", $accountOwner, $accountOwnerName, $selected, $lowLimit, $highLimit, $Rate, $wallet_to, $paymentMethod, $refrence);
            $buy_other_method = mysqli_stmt_execute($buy_other_Stmt);

            $stmt = $conn->prepare("UPDATE wallet SET {$selected} = {$selected} - ? WHERE `user_id` = ?");
            $stmt->bind_param('ds', $highLimit, $accountOwner);
            $removed = $stmt->execute();
            $stmt->close();

            if ($buy_other_method) {
                $posted['wallet'] = $selected;
                $posted['low_limit'] = $lowLimit;
                $posted['high_limit'] = $highLimit;
                $posted['rate'] = $Rate;
                $posted['wallet_to'] = $wallet_to;
                $posted['payment_method'] = $paymentMethod;
                $response["Successful"] = $posted;
            } else {
                // Handle the error case when the query execution fails
                $response['Failed'] = "Failed to post sell order";
            }
            mysqli_stmt_close($buy_other_Stmt);
        }elseif ( $selected == '' || $wallet_to == '') {
            $response['Empty'] = "One of Wallet-from or wallet-to canot be Empty";
        }elseif($userBalance < $highLimit){
            $response['Insufficient'] = "Insufficient wallet balance";
        }
         else {
            $response['Same'] = "Wallet-from and wallet-to canot be thesame";
        }
    }

    ////////////////////////////////////SELL OTHER METHOD/////////////////////////////////////

    if ($_POST['type'] === 'sell_other') {
        $wallet_to = mysqli_real_escape_string($conn, $_POST['wallet_to']);

        if ($selected !== $wallet_to && $selected != '' && $wallet_to != '' && $userBalance >= $highLimit) {
            $refrence = substr(md5(uniqid(rand(), true)), 0, 20);
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM p2p_post_buy_other_method WHERE buy_other_refrence = ?");
            $stmt->bind_param('s', $refrence);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                $refrence = substr(md5(uniqid(rand(), true)), 0, 20);
            }

            // Prepare the query using prepared statements for better security
            $sell_other = "INSERT INTO p2p_post_buy_other_method (`user_id`, `user_name`, `wallet`, `lowest_rate`, `highest_rate`, `user_rate`,`wallet_to`, `payment_method`,`buy_other_refrence`)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $sell_other_Stmt = mysqli_prepare($conn, $sell_other);
            mysqli_stmt_bind_param($sell_other_Stmt, "issssssss", $accountOwner, $accountOwnerName, $selected, $lowLimit, $highLimit, $Rate, $wallet_to, $paymentMethod, $refrence);
            $sell_other_method = mysqli_stmt_execute($sell_other_Stmt);


            //Function to check the currency type and remove the appropiate fee
            $p2pFee = removeFee($selected,$highLimit);
            
            $p2pFeeUpdate = $conn->prepare("UPDATE `p2p_fee` SET {$selected} = {$selected} + ?");
            $p2pFeeUpdate->bind_param('d',$p2pFee);
            $p2pfeeUpdated = $p2pFeeUpdate->execute();
            $p2pFeeUpdate->close();


            $stmt = $conn->prepare("UPDATE wallet SET {$selected} = {$selected} - ? WHERE `user_id` = ?");
            $stmt->bind_param('ds', $highLimit, $accountOwner);
            $removed = $stmt->execute();
            $stmt->close();

            if ($sell_other_method) {
                $posted['wallet'] = $selected;
                $posted['low_limit'] = $lowLimit;
                $posted['high_limit'] = $highLimit;
                $posted['rate'] = $Rate;
                $posted['wallet_to'] = $wallet_to;
                $posted['payment_method'] = $paymentMethod;
                $response["Successful"] = $posted;
            } else {
                // Handle the error case when the query execution fails
                $response['Failed'] = "Failed to post sell order";
            }
            mysqli_stmt_close($sell_other_Stmt);
        }elseif ( $selected == '' || $wallet_to == '') {
            $response['Empty'] = "One of Wallet-from or wallet-to canot be Empty";
        }elseif ($userBalance < $highLimit) {
            $response['Insufficient'] = "Insufficient wallet balance";
        }
         else {
            $response['Same'] = "Wallet-from and wallet-to canot be thesame";
        }
    }
}
echo json_encode($response);
