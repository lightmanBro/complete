<?php
require('../server.php');
// session_start();
$accountOwner = $_SESSION['id'];
if($_SERVER['REQUEST_METHOD']==="POST"){
    function clean_Input($userInpt){
        $userInpt = trim($userInpt);
        $userInpt = strip_tags($userInpt);
        $userInpt = stripslashes($userInpt);
        $userInpt = htmlspecialchars($userInpt);
        return $userInpt;
    }

    $trade_type = clean_Input($_POST['trade_type']);
    $buyer_id = clean_Input($_POST['buyer_id']);
    $wallet = clean_Input($_POST['wallet']);
    $order_unit = clean_Input($_POST['unit_amount']) *1;
    $receive_amount = clean_Input($_POST['cost']) *1;
    $exchangeRate = clean_Input($_POST['exchange_rate']) *1;
    $orderIndex = clean_Input($_POST['trade_index']) *1;
    $transaction_fee = clean_Input($_POST['transaction_fee'])*1;
    // $date = clean_Input($_POST['time']);
    $tradeCost = $receive_amount + $transaction_fee;

/*First insert the data into the cancelled trade table in the database*/
$cancelledTrade="INSERT INTO `cancelled_trade`(
    `trade_type`,
    `wallet`,
    `sender_id`,
    `receiver_id`,
    `trade_cost`,
    `exchange_rate`,
    `order_unit`,
    `transaction_fee`,
    `receive_amount`) VALUES (
    '$trade_type',
    '$wallet',
    '$accountOwner',
    '$buyer_id',
    '$tradeCost',
    '$exchangeRate',
    '$order_unit',
    '$transaction_fee',
    '$receive_amount')";
    $complete = mysqli_query($conn,$cancelledTrade);
    
/*Then delete the data from the table it was so it will be removed from the pending trade queue*/
    // $deleteTrade = "DELETE FROM `p2p_buy_order` WHERE `ind` = $tradeIndex"
    $deleteTrade = "DELETE FROM `p2p_buy_order` WHERE ind = $orderIndex";
    $delete = mysqli_query($conn,$deleteTrade);
}
echo json_encode($_POST);
?>