<?php
require('../server.php');

$accountOwner = $_SESSION['user_id'];

if (isset($_POST['release_fund'])) {
    function clean_Input($userInput)
    {
        $userInput = trim($userInput);
        $userInput = strip_tags($userInput);
        $userInput = stripslashes($userInput);
        $userInput = htmlspecialchars($userInput);
        return $userInput;
    }
    $data = [];
    // Sanitize and validate input data
    $buyer_id = clean_Input($_POST['buyer_id']);
    $buyerWallet = clean_Input($_POST['wallet_to_remove_from']);
    $myWallet = clean_Input($_POST['wallet']);
    $order_ref = clean_Input($_POST['order_refrence']);
    $order_unit = intval($_POST['order_unit']);
    $exchange_rate = floatval($_POST['exchange_rate']);
    $tradeCost = $order_unit * $exchange_rate;
    $trade_type = 'Sellother';
    $transaction_fee = $exchange_rate * $order_unit * 0.01;

    //Check if the order unit is lower than the highest_rate, then make the transaction else return insufficient fund
    //Use prepared statements to prevent SQL injection
    $checkBalQuery = "SELECT `highest_rate` FROM `p2p_post_sell_other_method` WHERE `wallet` = ? AND user_id = ?";
    $checkBalStmt = $conn->prepare($checkBalQuery);
    $checkBalStmt->bind_param("si", $buyerWallet, $accountOwner);
    $checkBalStmt->execute();
    $balResult = $checkBalStmt->get_result();
    
    if ($balResult->num_rows > 0) {
        while ($row = $balResult->fetch_assoc()) {
            $data['order'] = $row;
            //If the order unit is lower or equal to the highest rate then
            if ($row['highest_rate']>= $order_unit) {
                // Working
                $addValueQuery = "UPDATE wallet SET {$buyerWallet} = {$buyerWallet} + ? WHERE user_id = ?";
                $addValueStmt = $conn->prepare($addValueQuery);
                $addValueStmt->bind_param("ii", $order_unit, $buyer_id);
                $added = $addValueStmt->execute();

                /*Remove the value from the p2p buy highLimit and add it to the buyers value*/
                $removeValueQuery = "UPDATE p2p_post_sell_other_method SET highest_rate  = highest_rate - ? WHERE user_id = ? AND wallet = ?";
                $removeValueStmt = $conn->prepare($removeValueQuery);
                $removeValueStmt->bind_param("iis", $order_unit, $accountOwner, $buyerWallet);
                $removed = $removeValueStmt->execute();

                /*First insert the data into the completed trade table in the database*/
                $completedTradeQuery = "INSERT INTO 
                `p2p_transaction`(`seller_id`, `buyer_id`, `type`, `wallet_from`, `wallet_to`, `exchange_rate`, `amount`, `unit`, `transaction_fee`, `transaction_reference`) VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $completedTradeStmt = $conn->prepare($completedTradeQuery);
                $completedTradeStmt->bind_param("iisssddids", $accountOwner, $buyer_id, $trade_type, $myWallet, $buyerWallet, $exchange_rate, $tradeCost, $order_unit, $transaction_fee, $order_ref);
                $complete = $completedTradeStmt->execute();

                /*Then delete the data from the table it was so it will be removed from the pending trade queue*/
                //$deleteTrade = "DELETE FROM `p2p_buy_order` WHERE `ind` = $tradeIndex"
                $deleteTradeQuery = "DELETE FROM `p2p_order` WHERE `transaction_refrence` = ? AND seller_id = ?";
                $deleteTradeStmt = $conn->prepare($deleteTradeQuery);
                $deleteTradeStmt->bind_param("si", $order_ref, $accountOwner);
                $delete = $deleteTradeStmt->execute();

                $deleteImageQuery = "DELETE FROM `proof_images` WHERE `receiver_id` = ? AND refrence = ?";
                $deleteImageStmt = $conn->prepare($deleteImageQuery);
                $deleteImageStmt->bind_param("is",$accountOwner,$order_ref);
                $deleteImg = $deleteImageStmt->execute();

                if ($added && $removed) {
                    $data['success'] = 'Successful';
                }
            } else {
                $data['Failed'] = "Insufficient Balance";
            }
        }
    }

    // Output the result securely
    echo json_encode($data);
}
?>
