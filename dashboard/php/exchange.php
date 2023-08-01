<?php
//The p2p wallet to wallet automatic purchase;

//If currency is Dollar, and below 100, transaction fee = fee - 0.5 * cost else transaction fee = fee - 1;
//else transaction fee = fee * 0.05;

/*
Note : on wallet to wallet, fee will be deducted from both the seller and the buyer
check if the order unit is not lesser than the low-limit
*/

include('server.php');
function clean_Input($userInpt)
{
    $userInpt = trim($userInpt);
    $userInpt = strip_tags($userInpt);
    $userInpt = stripslashes($userInpt);
    $userInpt = htmlspecialchars($userInpt);
    return $userInpt;
}

$refrence = substr(md5(uniqid(rand(), true)), 0, 25); // order reference number
$UniqueRef = "SELECT COUNT(*) as count FROM transactions WHERE transaction_refrence = ?";
$stmt = $conn->prepare($UniqueRef);
$stmt->bind_param('s', $refrence);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

$_SESSION['count'] = $count;
if ($count > 0) {
    $refrence = substr(md5(uniqid(rand(), true)), 0, 25);
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $type = clean_Input($_POST['Type']);

    if ($_POST['Type'] === 'buyWallet') {
        $sellerId = clean_Input($_POST['SellerId']);
        $wallet = clean_Input($_POST['BuyerWallet']);
        $sellerWallet = clean_Input($_POST['SellerWallet']);
        $high_limit = clean_Input($_POST['high_limit']);
        $low_limit = clean_Input($_POST['low_limit']);
        $orderUnit = clean_Input($_POST['OrderUnit']);
        $OrderCost = clean_Input($_POST['Cost']);
        $orderRef = clean_Input($_POST['order_ref']);
        $receiveAmount = clean_Input($_POST['ReceiveAmount']);
        $transactionFee = clean_Input($_POST['TransactionFee']);
        $exchangeRate = $OrderCost / $orderUnit;
        $type = 'Buy';

        //Check if this user balance is greater than the order unit;
        $stmt = $conn->prepare("SELECT {$wallet} FROM `wallet` WHERE user_id = ?");
        $stmt->bind_param('s', $_SESSION['user_id']);
        $stmt->execute();
        $stmt->bind_result($userBalance);
        $stmt->fetch();
        $stmt->close();

        //Check the p2p values to see if the order cost is not lower or higher than the high limits
        $check = $conn->prepare("SELECT `lowest_rate`, `highest_rate` FROM `p2p_posts_buy` WHERE user_id = ? AND buy_order_refrence = ?");
        $check->bind_param('is', $sellerId, $orderRef);
        $check->execute();
        $check->bind_result($low, $high);
        $check->fetch();
        $check->close();


        //Check if the cost of the order is not greater than the high limit
        //If the cost of the order is equal to the highlimit then do the transaction and delete the order from the p2p table
        if ($OrderCost >= $low && $OrderCost <= $high) {
            if ($orderUnit <= $userBalance) {

                //Confirm the user id from the database;
                $stmt = $conn->prepare("SELECT user_id FROM `user_credentials` WHERE `user_id` = ?");
                $stmt->bind_param('s', $sellerId);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    //Add to the order value to seller account
                    $stmt = $conn->prepare("UPDATE wallet SET {$wallet} = {$wallet} + ? WHERE `user_id` = ?");
                    $stmt->bind_param('ds', $orderUnit, $sellerId);
                    $added = $stmt->execute();

                    //Remove the value from the buyer account
                    $stmt = $conn->prepare("UPDATE wallet SET {$wallet} = {$wallet} - ? WHERE `user_id` = ?");
                    $stmt->bind_param('ds', $orderUnit, $_SESSION['user_id']);
                    $removed = $stmt->execute();


                    //Add the receive amount to buyer account
                    $stmt = $conn->prepare("UPDATE wallet SET {$sellerWallet} = {$sellerWallet} + ? WHERE `user_id` = ?");
                    $stmt->bind_param('ds', $receiveAmount, $_SESSION['user_id']);
                    $added = $stmt->execute();

                    //Save the transaction fee inside the p2p buy fee
                    $p2pFeeUpdate = $conn->prepare("UPDATE `p2p_buy_fee` SET {$sellerWallet} = {$sellerWallet} + ?");
                    $p2pFeeUpdate->bind_param('d', $transactionFee);
                    $p2pfeeUpdated = $p2pFeeUpdate->execute();
                    $p2pFeeUpdate->close();


                    //Remove order cost from the seller account ()
                    $stmt = $conn->prepare("UPDATE wallet SET {$sellerWallet} = {$sellerWallet} - ? WHERE `user_id` = ?");
                    $stmt->bind_param('ds', $OrderCost, $sellerId);
                    $removed = $stmt->execute();

                    //The trade showing in the buy dashboard is stored on the sell table
                    $stmt = $conn->prepare("UPDATE p2p_posts_buy SET highest_rate = highest_rate - ? WHERE `user_id` = ? AND `buy_order_refrence` = ?");
                    $stmt->bind_param('dsd', $OrderCost, $sellerId, $orderRef);
                    $removed = $stmt->execute();


                    $stmt->close();

                    //Transaction fee from buyer and seller
                    $stmt = $conn->prepare(
                        "INSERT INTO `p2p_transaction`(
                        `seller_id`,
                        `buyer_id`,
                        `type`,
                        `wallet_from`,
                        `wallet_to`,
                        `exchange_rate`,
                        `amount`,
                        `unit`,
                        `transaction_fee`,
                        `transaction_reference`
                        )
                        VALUES
                        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                    );
                    $stmt->bind_param(
                        'ddsssdddds',
                        $sellerId,
                        $_SESSION['user_id'],
                        $type,
                        $sellerWallet,
                        $wallet,
                        $exchangeRate,
                        $receiveAmount,
                        $orderUnit,
                        $transactionFee,
                        $refrence
                    );
                    $result = $stmt->execute();

                    if ($added && $result) {
                        $response['type'] = "buy";
                        $response["Successful"] = "Sent " . $orderUnit . " " . $wallet . " to " . $sellerId . " You have received " . $receiveAmount . "in your" . $sellerWallet . " wallet";
                    
                    } else {
                        $response['type'] = "buy";
                        $response["Not_Successful"] = "Transaction was not successful";
                    
                    }
                    $stmt->close();
                } else {
                    $response['type'] = "buy";
                    $response["user_not_found"] = "User not found";
                
                }
            } else if ($OrderCost > $high) {
                $response['type'] = "buy";
                $response['Too_low'] = "Cost is higher than balance";
            } else {
                $response['type'] = "buy";
                $response["Insufficient_Balance"] = "Insufficient Balance";
            
            }
        } else {
            $response['type'] = "buy";
            $response['not_equal'] = 'Order unit must be between higher or lower limit';
        }
    } elseif ($_POST['Type'] === 'sellWallet') {


        `const sellOrder = new FormData();
        sellOrder.append("Type", "sellWallet");
        sellOrder.append("order_ref", sell_order_refrence);
        sellOrder.append("Cost", purchaseCost);
        sellOrder.append("high_limit", high_limit.innerHTML);
        sellOrder.append("low_limit", low_limit.innerHTML);
        sellOrder.append("TransactionFee", checkFee * 1);
        sellOrder.append("ReceiveAmount", receiveAmount * 1);
        sellOrder.append("buyerId", sellerId);
        sellOrder.append("OrderUnit", orderUnit.value.trim() * 1);
        sellOrder.append("BuyerWallet", my_Wallet.innerHTML);
        sellOrder.append("SellerWallet", seller_wallet);`;

        $sellerId = clean_Input($_POST['buyerId']);
        $wallet = clean_Input($_POST['BuyerWallet']);
        $sellerWallet = clean_Input($_POST['SellerWallet']);
        $orderRef = clean_Input($_POST['order_ref']);
        $high_limit = clean_Input($_POST['high_limit']);
        $low_limit = clean_Input($_POST['low_limit']);
        $orderUnit = clean_Input($_POST['OrderUnit']);
        $OrderCost = clean_Input($_POST['Cost']);
        $receiveAmount = clean_Input($_POST['ReceiveAmount']);
        $transactionFee = clean_Input($_POST['TransactionFee']);
        $exchangeRate = $OrderCost / $orderUnit;
        $type = 'Sell';

        $stmt = $conn->prepare("SELECT {$wallet} FROM `wallet` WHERE user_id = ?");
        $stmt->bind_param('s', $_SESSION['user_id']);
        $stmt->execute();
        $stmt->bind_result($userBalance);
        $stmt->fetch();
        $stmt->close();


        $check = $conn->prepare("SELECT `lowest_rate`, `highest_rate` FROM `p2p_posts_sell` WHERE user_id = ? AND sell_order_refrence = ?");
        $check->bind_param('is', $sellerId, $orderRef);
        $check->execute();
        $check->bind_result($low, $high);
        $check->fetch();
        $check->close();


        //Check if the cost of the order is not greater than the high limit
        //If the cost of the order is equal to the highlimit then do the transaction and delete the order from the p2p table
        if ($OrderCost >= $low && $OrderCost <= $high) {

            if ($userBalance >= $orderUnit) {
                $stmt = $conn->prepare("SELECT user_id FROM `user_credentials` WHERE `user_id` = ?");
                $stmt->bind_param('s', $sellerId);
                $stmt->execute();
                $stmt->store_result();
        
                if ($stmt->num_rows > 0) {
                    //Remove the value from the buyer account
                    $stmt = $conn->prepare("UPDATE wallet SET {$wallet} = {$wallet} - ? WHERE `user_id` = ?");
                    $stmt->bind_param('ds', $orderUnit, $_SESSION['user_id']);
                    $removed = $stmt->execute();

                    //Add to the seller account
                    $stmt = $conn->prepare("UPDATE wallet SET {$sellerWallet} = {$sellerWallet} + ? WHERE `user_id` = ?");
                    $stmt->bind_param('ds', $receiveAmount, $_SESSION['user_id']);
                    $added = $stmt->execute();

                    //Add to the order value to seller account
                    $stmt = $conn->prepare("UPDATE wallet SET {$wallet} = {$wallet} + ? WHERE `user_id` = ?");
                    $stmt->bind_param('ds', $orderUnit, $sellerId);
                    $added = $stmt->execute();

                    //Save the transaction fee inside the p2p buy fee
                    $p2pFeeUpdate = $conn->prepare("UPDATE `p2p_sell_fee` SET {$sellerWallet} = {$sellerWallet} + ?");
                    $p2pFeeUpdate->bind_param('d', $transactionFee);
                    $p2pfeeUpdated = $p2pFeeUpdate->execute();
                    $p2pFeeUpdate->close();

                    //Remove from the seller account ()
                    $stmt = $conn->prepare("UPDATE wallet SET {$sellerWallet} = {$sellerWallet} - ? WHERE `user_id` = ?");
                    $stmt->bind_param('ds', $OrderCost, $sellerId);
                    $removed = $stmt->execute();

                    // Update the new value
                    $stmt = $conn->prepare("UPDATE p2p_posts_sell SET highest_rate = highest_rate - ? WHERE `user_id` = ? AND `sell_order_refrence` = ?");
                    $stmt->bind_param('dsd', $OrderCost, $sellerId, $orderRef);
                    $removed = $stmt->execute();

                    $stmt = $conn->prepare(
                        "INSERT INTO `p2p_transaction`(
                        `seller_id`,
                        `buyer_id`,
                        `type`,
                        `wallet_from`,
                        `wallet_to`,
                        `exchange_rate`,
                        `amount`,
                        `unit`,
                        `transaction_fee`,
                        `transaction_reference`
                        )
                        VALUES
                        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                    );
                    $stmt->bind_param(
                        'ddsssdddds',
                        $sellerId,
                        $_SESSION['user_id'],
                        $type,
                        $sellerWallet,
                        $wallet,
                        $exchangeRate,
                        $receiveAmount,
                        $orderUnit,
                        $transactionFee,
                        $refrence
                    );
                    $result = $stmt->execute();

                    if ($added && $result) {
                        $response['type'] = "sell";
                        $response["Successful"] = "Sent " . $orderUnit . " " . $wallet . " to " . $sellerId . "You have received " . $receiveAmount . "in your" . $sellerWallet . " wallet";
                    } else {
                        $response['type'] = "sell";
                        $response["Not_Successful"] = "Transaction was not successful";
                    }
                } else {
                    $response['type'] = "sell";
                    $response["user_not_found"] = "User not found";
                }
            } else {
                $response['type'] = "sell";
                $response["Insufficient_Balance"] = "Insufficient Balance";
            }
        } else {
            $response['type'] = "sell";
            $response['not_equal'] = 'Order unit must be between higher or lower limit';
        }
    }
    echo json_encode($response);
}
