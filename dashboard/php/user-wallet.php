<?php
include('server.php');
// session_start();
$userId= $_SESSION['user_id'];
// echo $_SERVER['REQUEST_METHOD'];

$sql = "SELECT Naira,Dollar,Cedi,Rand FROM wallet WHERE user_id = $userId";
$userBal = mysqli_query($conn,$sql);
if($userBal){
    $row = mysqli_fetch_assoc($userBal);
    //turn the data received from the database into a session variable;
    $_SESSION['wallet_balance']=$row;
    echo json_encode($row);
    // print_r($userId);
}

?>