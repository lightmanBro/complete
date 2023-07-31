<?php
//Connected to history/fetTrades.js

require('../server.php');
$accountOwner = $_SESSION['id'];
$history = array(
    "received" => "",
    "sent" => "",
);

//Select all from Completed trade;
$sql = "SELECT * FROM `transactions` WHERE receiver_id  = $accountOwner";
$completedTrade = mysqli_query($conn, $sql);

if ($completedTrade->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while ($row = $completedTrade->fetch_assoc()) {
        //variable,arraydata
        // array_push($data, $row);
        $completed[] = $row;
        $history['received'] = $completed;
    }
}

//UPDATE `active_trade` SET `ind`='[value-1]',`user_id`='[value-2]',`trade_id`='[value-3]',`trade_value`='[value-4]',`trade_rate`='[value-5]',`date`='[value-6]' WHERE 1

//Select all from disputed trade
$sql = "SELECT * FROM `transactions`  WHERE sender_id = $accountOwner";
$activeTrade = mysqli_query($conn, $sql);

if ($activeTrade->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while ($row = $activeTrade->fetch_assoc()) {
        $active[] = $row;
        $history['sent'] = $active;
    }
}

//Select all from Completed trade
echo json_encode($history);
