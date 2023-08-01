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
    $bankName = clean_Input($_POST['bank_name']);
    $accountName = clean_Input($_POST['Account_name']);
    $accountNumber = clean_Input($_POST['account_number']);
    $sql = "INSERT INTO `bank`(`user_id`, `bank_name`, `user_name`, `account_number`) VALUES ('$accountOwner','$bankName','$accountName','$accountNumber')";
    $bank = mysqli_query($conn,$sql);
    // print_r($accType);
}
?>