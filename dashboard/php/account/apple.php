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

    $appleName = clean_Input($_POST['apple_name']);
    $appleMail = clean_Input($_POST['apple_mail']);
    $sql = "INSERT INTO `apple_pay`(`user_id`, `apple_email`) VALUES ('$accountOwner','$appleMail')";
    $apple = mysqli_query($conn,$sql);
}
?>