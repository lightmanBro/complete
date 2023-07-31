<?php

require('../server.php');
$accountOwner = $_SESSION['id'];
$history = array(
    "active" => "",
    "completed" => "",
    "disputed" => "",
    "cancelled" => "",
    "pending" => "",
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

//Select all from disputed trade
$sql = "SELECT * FROM `p2p_buy_order` WHERE seller_id = $accountOwner";
$activeTrade = mysqli_query($conn, $sql);

if ($activeTrade->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while ($row = $activeTrade->fetch_assoc()) {
        $active[] = $row;
        $history['active'] = $active;
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
$pendingTrade = mysqli_query($conn, $sql);

if ($pendingTrade->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while ($row = $pendingTrade->fetch_assoc()) {
        $pending[] = $row;
        $history['pending'] = $pending;
    }
}

//Select all from Completed trade
echo json_encode($history);
