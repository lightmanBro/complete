<?php

include('server.php');
 //session_start();
 $userId= $_SESSION['user_id'];
//The session already started inside the Home php can also be used here without starting another new session
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
?>