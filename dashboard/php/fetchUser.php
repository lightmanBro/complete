<?php
//connecteď to fetchDetails.js
include('server.php');
// session_start();//The session already started inside the Home php can also be used here without starting another new session
function clean_Input($userInpt){
    $userInpt = trim($userInpt);
    $userInpt = strip_tags($userInpt);
    $userInpt = stripslashes($userInpt);
    $userInpt = htmlspecialchars($userInpt);
    return $userInpt;
}
$response = array("Not_found"=>"",);
if($_SERVER['REQUEST_METHOD']=== 'POST'){

    $receiver = clean_Input($_POST['user_name']);
    $sql = "SELECT
    `user_id`,
    `first_name`,
    `middle_name`,
    `last_name`
    FROM 
    register.user_credentials
    WHERE user_id = '$receiver'";
    $user_credentials = mysqli_query($conn,$sql);
    if($user_credentials){
        $row = mysqli_fetch_assoc($user_credentials);
        $firstName=$row['first_name'];
        $middleName=$row['middle_name'];
        $lastName=$row['last_name'];
        $userId=$row['user_id'];

        // $_SESSION['name']= $name;
        // $_SESSION['id'] = $id;
        echo json_encode($row);
    }



}

?>