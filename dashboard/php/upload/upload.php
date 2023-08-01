<?php
require('../server.php');
$img = array("image" => "");
$accountOwner = $_SESSION['user_id'];
$uploadData = [];
$upd = [];
$upld = [];
// print_r($_FILES);
if ($_FILES['image']) {
    $tempName = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];
    // Move the uploaded file to the desired location
    // move_uploaded_file($fileName,$tempName);
    $seller = $_POST['receiver'];
    $ref = $_POST['refrence'];
    $img["image"] = $fileName;
    $uploadData['img'] = $img['image'];
    $uploadData['receiver'] = $seller;
    /*
    1. Check the database if any image of thesame refrence is already there, if the image is there then update the image using the refrence
    if the image is not there then insert the image into the database;
    */
    $checkImage = "SELECT `refrence` FROM `proof_images` WHERE refrence = '$ref'";
    $imageAvalable = mysqli_query($conn, $checkImage);
    if ($imageAvalable->num_rows > 0){
        $update = "UPDATE `proof_images` SET`name_and_path`='$fileName' WHERE uploader = '$accountOwner'";
        $updated = mysqli_query($conn, $update);
        json_encode($uploadData);
        if($updated){
            $uploadData['img'] = $img['image'];
            move_uploaded_file($tempName, 'proofImg/' . $fileName);
            json_encode($uploadData);
        }
    } else {
        $upload = "INSERT INTO `proof_images` (`uploader`, `receiver_id`,`refrence`,`name_and_path`) VALUES ('$accountOwner','$seller','$ref','$fileName')";
        $uploaded = mysqli_query($conn, $upload);
        echo json_encode($uploadData);
        if ($uploaded) {
            $uploadData['img'] = $img['image'];
            move_uploaded_file($tempName, 'proofImg/' . $fileName);
            json_encode($uploadData);
        }else{
            $uploadFail['upload_failed'] = "Upload Failed, Try again";
            $uploadData['Failed'] = $uploadFail;
            echo json_encode($uploadData); 
        }
    };
} else {
    $uploadError['Error'] = "File not found";
    $uploadData['Err'] = $uploadError;
    echo json_encode($uploadData);
}

// json_encode($uploadData);
?>