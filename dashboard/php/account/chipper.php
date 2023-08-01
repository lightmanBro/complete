<?php

session_start();
$accountOwner = $_SESSION['user_id'];
$serverName = 'localhost';
$username = 'root';
$password = '';
$databaseName = 'register';
$conn = new mysqli($serverName,$username,$password,$databaseName);
if($conn){
    $userNameChipper = clean_Input($_POST['user_name']);
    $chipperMail = clean_Input($_POST['chipper_mail']);
    $sql = "INSERT INTO `chipper`(`user_id`,`user_name`) VALUES ('$accountOwner','$userNameChipper')";
    $bank = mysqli_query($conn,$sql);
    print_r($accType);
}
?>