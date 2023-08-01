<?php

session_start();
$accountOwner = $_SESSION['user_id'];
$serverName = 'localhost';
$username = 'root';
$password = '';
$databaseName = 'register';
$conn = new mysqli($serverName,$username,$password,$databaseName);

function clean_Input($userInpt){
    $userInpt = trim($userInpt);
    $userInpt = strip_tags($userInpt);
    $userInpt = stripslashes($userInpt);
    $userInpt = htmlspecialchars($userInpt);
    return $userInpt;
}
if($conn){
            $pi_Wallet = clean_Input($_POST['wallet_address']);
            $sql = "INSERT INTO `pi network`(`user_id`, `wallet_Address`) VALUES ('$accountOwner','$pi_Wallet')";
            $pi = mysqli_query($conn,$sql);

}

?>