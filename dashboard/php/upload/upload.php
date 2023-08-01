<?php
require('../server.php');
$img = array("image"=>"");
$accountOwner = $_SESSION['user_id'];
$uploadData = [];
if ($_FILES['image']) {
    $tempName = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];
    // Move the uploaded file to the desired location
    // move_uploaded_file($fileName,$tempName);
    $seller = $_POST['receiver'];
    $ref = $_POST['refrence'];
    $img["image"] = $fileName;
    $uploadData['img']= $img['image'];
    $uploadData['receiver']= $seller;
    move_uploaded_file($tempName,'proofImg/'.$fileName);

    $upload = "INSERT INTO `proof_images` (`uploader`, `receiver_id`,`refrence`,`name_and_path`) VALUES ('$accountOwner','$seller','$ref','$fileName')";
    $uploaded = mysqli_query($conn,$upload);
    if($uploaded){
        $uploadData['Success']= "Successful";
    }
    echo json_encode($uploadData);
} else {
    echo "Image upload failed.";
}
?>
