<?php

require('../server.php');
$accountOwner = $_SESSION['user_id'];
$history = array(
    "active" => "",
    "completed" => "",
    "disputed" => "",
    "cancelled" => "",
);

//Select all from Completed trade;
$sql = "SELECT * FROM `completed_trade` WHERE receiver_id  = $accountOwner";
$completedTrade = mysqli_query($conn, $sql);

if ($completedTrade->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while ($row = $completedTrade->fetch_assoc()) {
        //variable,arraydata
        // array_push($data, $row);
        $completed[] = $row;
        $history['completed'] = $completed;
    }
}

//UPDATE `active_trade` SET `ind`='[value-1]',`user_id`='[value-2]',`trade_id`='[value-3]',`trade_value`='[value-4]',`trade_rate`='[value-5]',`date`='[value-6]' WHERE 1

$sql = "SELECT * FROM `p2p_order` WHERE seller_id = $accountOwner";
$pendingTradeSell = mysqli_query($conn, $sql);

if ($pendingTradeSell->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while ($row = $pendingTradeSell->fetch_assoc()) {
        $pendingSell[] = $row;
        $history['pendingSell'] = $pendingSell;
    }
}

$sql = "SELECT * FROM `p2p_order` WHERE buyer_id = $accountOwner";
$pendingTradeBuy = mysqli_query($conn, $sql);

if ($pendingTradeBuy->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while ($row = $pendingTradeBuy->fetch_assoc()) {
        $pendingBuy[] = $row;
        $history['pendingBuy'] = $pendingBuy;
    }
}

//


//Select all from cancelled trade
$sql = "SELECT `wallet`,
`lowest_rate`,
`highest_rate`,
`user_rate`,
`payment_method`,
`date` FROM `p2p_posts_buy` WHERE user_id = $accountOwner";
$activeTrade = mysqli_query($conn, $sql);


if ($activeTrade->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while ($row = $activeTrade->fetch_assoc()) {
        $active[] = $row;
        $history['active'] = $active;
    }
}


$sql = "SELECT * FROM `p2p_post_buy_other_method` WHERE user_id = $accountOwner";
$activeTrade_buy_other = mysqli_query($conn, $sql);

if ($activeTrade_buy_other->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while ($row = $activeTrade_buy_other->fetch_assoc()) {
        $active_buy_o[] = $row;
        $history['buy_other'] = $active_buy_o;
    }
}

$sql = "SELECT * FROM `p2p_post_sell_other_method` WHERE user_id = $accountOwner";
$activeTrade_sell_other = mysqli_query($conn, $sql);

if ($activeTrade_sell_other->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while ($row = $activeTrade_sell_other->fetch_assoc()) {
        $active_sell_o[] = $row;
        $history['sell_other'] = $active_sell_o;
    }
}

$sql = "SELECT * FROM `p2p_transaction`  WHERE buyer_id = $accountOwner";
$p2pTrade = mysqli_query($conn, $sql);

if ($p2pTrade->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while ($row = $p2pTrade->fetch_assoc()) {
        $p2p[] = $row;
        $history['p2p'] = $p2p;
    }
}

$sql = "SELECT * FROM `p2p_transaction`  WHERE seller_id = $accountOwner";
$p2pTrade = mysqli_query($conn, $sql);

if ($p2pTrade->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while ($row = $p2pTrade->fetch_assoc()) {
        $p2pOther[] = $row;
        $history['p2p_other'] = $p2pOther;
    }
}

//Select all from Completed trade
echo json_encode($history);
