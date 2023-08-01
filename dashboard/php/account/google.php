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

    $googlemail = clean_Input($_POST['google_mail']);
    $sql = "INSERT INTO `google_pay`(`user_id`, `email_address`) VALUES ('$accountOwner','$googlemail')";
    $google = mysqli_query($conn,$sql);
}
?>