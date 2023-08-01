<?php
//Connect the server here and require it in the rest of the file
$serverName = 'localhost';
$username = 'root';
$password = 'password';
$databaseName = 'register';
session_start();
$conn = new mysqli($serverName,$username,$password,$databaseName);
if($conn){
    // echo "connected";
}else{
    die('connection failed'.$conn->connect_error);
}
?>