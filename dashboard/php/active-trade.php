<?php
// session_start();
include('server.php');
$accountOwner = $_SESSION['id'];
$wallets=$_SESSION['wallet_balance'];

// print_r($_POST);
// echo $_SERVER['REQUEST_METHOD'];

// $rate = $_POST['seller_rate'];
// $amount = $_POST['amount_to_buy'];

// print_r($name, $amount);
header("Content-Type:application/json");

$stmt2 = $conn->prepare("SELECT `Cedi` FROM `wallet` WHERE user_id =?");
$stmt2->bind_param('i',$accountOwner);
$stmt2->execute();
$stmt2->store_result();

if($stmt2){
    $stmt2->bind_result($userBalance);
    $stmt2->fetch();
    echo json_encode($wallets);
}
?>
