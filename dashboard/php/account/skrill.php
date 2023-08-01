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

    $skrillmail = clean_Input($_POST['skrill_mail']);
    $sql = "INSERT INTO `skrill`(`user_id`, `email_address`) VALUES ('$accountOwner','$skrillmail')";
    $skrill = mysqli_query($conn,$sql);
}
?>