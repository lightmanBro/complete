<?php
require('../server.php');
$accountOwner = $_SESSION['user_id'];
//Check the proof by the receiver id and transaction refrence

$proof = "SELECT * FROM proof_images WHERE receiver_id = '$accountOwner'";
    $uploadedProof = mysqli_query($conn,$proof);
    if($uploadedProof->num_rows>0){
        while ($row = $uploadedProof->fetch_assoc()) {
            // $data['proof_images'] = $row;
            $uploader = $row['uploader'];
            $nameAndRef = $row['name_and_path'];
            //Split the strings so i can get the specific order refrence
            $orderRef = explode("+",$nameAndRef);
            $ref = explode(".",$orderRef[1]);
            $ref[0];
            $data['buyer'] = $uploader;
            $data['path'] = $nameAndRef;
            $data['order_ref'] = $ref[0];
        }
        echo json_encode($data);
    }

    
?>