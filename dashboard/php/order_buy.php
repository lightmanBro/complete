<?php
// session_start();
include('server.php');
$accountOwner = $_SESSION['id'];
$data = array();
$response = array("Succesfull"=>"");
$wallets=$_SESSION['wallet_balance'];
$posts = array('Posts'=>'');


$stmt2 = "SELECT * FROM `p2p_posts_buy` ORDER BY date DESC";
$result = mysqli_query($conn,$stmt2);

//more like if(result.num_rows > 0) in js.
if ($result->num_rows > 0) {
    //turned the data variable into an array variable

    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while($row = $result->fetch_assoc()) {
              //variable,arraydata
      // array_push($data, $row);
      $dataRes[] =$row;
      $data['one']= $dataRes;
    }
    //convert data array to json format
  }
  $response['Succesfull'] = "Buy order Posted succesfully";
  



$stmtOther = "SELECT * FROM `p2p_post_buy_other_method` ORDER BY `time` DESC";
$otherResult = mysqli_query($conn,$stmtOther);

//more like if(otherResult.num_rows > 0) in js.
if ($otherResult->num_rows > 0) {
    //turned the data variable into an array variable

    //check if the row contain the otherResult and turn the otherResult to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while($row = $otherResult->fetch_assoc()) {
              //variable,arraydata
      // array_push($data, $row);
      $dataOther[] =$row;
      $data['other']= $dataOther;
    }
    //convert data array to json format
  }
  // $posts['Post'] = $_POST;
  $data['two'] = $response;
  $data['three'] = $accountOwner;
  
  echo json_encode($data);
  //close the database connection
//   $conn->close();  
