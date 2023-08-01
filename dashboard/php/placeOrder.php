<?php



include('server.php');


function removeFee($select, $highLimit)
{
    $fee = 0.008;
    //Based on the currency posted for p2p, remove the post fee;
    if ($select == 'Dollar' && $highLimit < 100) $fee = 0.5;
    if ($select == 'Dollar' && $highLimit >= 100) $fee = 1;
    return $fee;
};
$response = array();

function clean_Input($userInpt)
{
    $userInpt = trim($userInpt);
    $userInpt = strip_tags($userInpt);
    $userInpt = stripslashes($userInpt);
    $userInpt = htmlspecialchars($userInpt);
    return $userInpt;
}

// Fetch the account owner's ID from the session
$accountOwner = $_SESSION['user_id'];

// Generate a unique order reference number
$refrence = substr(md5(uniqid(rand(), true)), 0, 25);
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $response = array("failed" => "", "Success" => "");


    if ($_POST['Type'] === 'buyOther') {
        // Processing buyOther type transaction
        // Extract data from the POST request
        $sellerId = clean_Input($_POST['seller_id']);
        $sellerWallet = clean_Input($_POST['SellerWallet']);
        $orderUnit = clean_Input($_POST['OrderUnit']);
        $buyerWallet = clean_Input($_POST['my_wallet']);
        $exchangeRate = clean_Input($_POST['exchange_rate']);
        $paymentMethod = clean_Input($_POST['method']);
        $orderRef = clean_Input($_POST['order_ref']);
        $OrderCost = $orderUnit * $exchangeRate;
        $transactionFee = removeFee($sellerWallet, $orderUnit) * $OrderCost;
        $type = "Buy Other";

        // Check the p2p values to see if the order cost is within the specified limits
        $check = $conn->prepare("SELECT `lowest_rate`, `highest_rate` FROM `p2p_post_buy_other_method` WHERE user_id = ? AND buy_other_refrence = ?");
        $check->bind_param('is', $sellerId, $orderRef);
        $check->execute();
        $check->bind_result($low, $high);
        $check->fetch();
        $check->close();

        // TODO: Handle different cases for buyOther type transaction
        if ($OrderCost >= $low && $OrderCost <= $high) {
            $stmt = $conn->prepare("UPDATE p2p_post_buy_other_method SET highest_rate = highest_rate - ? WHERE `user_id` = ? AND `buy_other_refrence` = ?");
            $stmt->bind_param('dsd', $OrderCost, $sellerId, $orderRef);
            $removed = $stmt->execute();

            //TODO: Implement the rest of the buyOther transaction logic here
            $placeOrder = $conn->prepare("INSERT INTO `p2p_order`(
            `seller_id`, 
            `buyer_id`, 
            `type`, 
            `wallet`, 
            `wallet_to`, 
            `payment_method`, 
            `order_unit`, 
            `exchange_rate`, 
            `transaction_refrence`,
            `transaction_fee`)
             VALUES (?,?,?,?,?,?,?,?,?,?)");
            $placeOrder->bind_param(
                'iissssiiss',
                $sellerId,
                $accountOwner,
                $type,
                $sellerWallet,
                $buyerWallet,
                $paymentMethod,
                $orderUnit,
                $exchangeRate,
                $orderRef,
                $transactionFee
            );
            $ordered = $placeOrder->execute();

            if ($ordered) {
                $response['Success'] = "Placed the order";
            } else {
                $response['failed'] = "Failed";
            }
        }
    };
    echo json_encode($transactionFee);
}
