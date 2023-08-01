<?php
//Connected to Home.js
//This fetched the user id and name so that it can be used all over the pages;
include('server.php');
 $userId= $_SESSION['user_id'];
$sql = "SELECT * FROM .user_credentials WHERE user_id = $userId";
$user_credentials = mysqli_query($conn,$sql);
if($user_credentials){
    $row = mysqli_fetch_assoc($user_credentials);
    $firstName=$row['first_name'];
    $middleName=$row['middle_name'];
    $userId=$row['user_id'];
    //$_SESSION['name']= $name;
    //$_SESSION['id'] = $id;
    echo json_encode($row);
    //print_r($_SESSION['user_id']);
}
