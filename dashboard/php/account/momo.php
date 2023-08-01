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
    $momoName = clean_Input($_POST['momo_name']);
    $momonumber = clean_Input($_POST['momo_number']);
    $sql = "INSERT INTO `momo`(`user_id`, `user_name`, `account_number`) VALUES ('$accountOwner','$momoName','$momonumber')";
    $bank = mysqli_query($conn,$sql);
}

?>