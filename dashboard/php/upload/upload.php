<?php
require('../server.php');
$img = array("image"=>"");
if ($_FILES['image']) {
    // $tempName = $_FILES['image']['tmp_name'];
    // $fileName = $_FILES['image']['name'];
    
    // Set the destination directory
    // $destination = 'php/upload/'.$fileName;

    $tempName = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];
    move_uploaded_file($tempName,'proofImg/'.$fileName);
    
    // Move the uploaded file to the desired location
    // move_uploaded_file($fileName,$tempName);
    $img["image"] = $fileName;
    
    echo json_encode($img);
} else {
    echo "Image upload failed.";
}
?>
