<?php

session_start();
$accountOwner = $_SESSION['id'];
$serverName = 'localhost';
$username = 'root';
$password = 'password';
$databaseName = 'tamopei payment options';
$conn = new mysqli($serverName,$username,$password,$databaseName);
function clean_Input($userInpt){
    $userInpt = trim($userInpt);
    $userInpt = strip_tags($userInpt);
    $userInpt = stripslashes($userInpt);
    $userInpt = htmlspecialchars($userInpt);
    return $userInpt;
}

if($conn){

    $paypalmail = clean_Input($_POST['paypal_email']);
    $sql = "INSERT INTO `paypal`(`user_id`, `email_address`) VALUES ('$accountOwner','$paypalmail')";
    $paypal = mysqli_query($conn,$sql);
}
?>