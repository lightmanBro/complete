<?php
include('server.php');
$response = $_SESSION['response'];
print_r($_SESSION['count']);
// print_r($unique_id);
if($response == "Insufficient Balance"){
    echo '<h3 class="Insufficient">
    <span class="close">close</span> Insufficient Balance 😥</h3>';
 }elseif($response == "User not found"){
     echo '<h3 class="sufficient">
     <span class="close">close</span>
     User not be Found 🤔</h3>';
 }

 $a = "";
 $b = "ok";
 if($a == "" && $b){
    echo $b;
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .Insufficient{
            position:relative;
            width:40%;
            height:auto;
            background-color:rgb(76, 76, 76);
            margin:auto;
            text-align:center;
            font-size: 100px;
            color: #bdc3c77b;
            padding:100px;
            border:5px solid grey;
        }
        .sufficient{
            position:relative;
            width:40%;
            height:auto;
            background-color:grey;
            margin:auto;
            text-align:center;
            font-size: 100px;
            color: #bdc3c77b;
            padding:100px;
        }
        .close{
            position:absolute;
            top:50px;
            left:50px;
            border:2.5px solid black;
            padding:10px;
            color: #000;
            font-size: 20px;
            cursor:pointer;
        }
    </style>
</head>
<body>
    <!-- <h2>Notification Page</h2> -->
</body>
</html>
<script src="./scripts/sendToDb.js"></script>
<!-- payment gateway\scripts\sendToDb.js -->
<script>

    document.querySelector('.close').addEventListener('click',()=>{

    })
    // console.log(response);
</script>