<?php
//Connected to trade.js
session_start();
$accountOwner = $_SESSION['id'];
$serverName = 'localhost';
$username = 'root';
$password = '';
$databaseName = 'register';
$conn = new mysqli($serverName,$username,$password,$databaseName);
$wallets=$_SESSION['wallet_balance'];


$Paymentmethod = [];

$apple = "SELECT apple_email FROM apple_pay WHERE user_id = $accountOwner";
$appleresult = mysqli_query($conn,$apple);

$bank = "SELECT bank_name, user_name, account_number FROM bank WHERE user_id = $accountOwner";
$bankresult = mysqli_query($conn,$bank);

$google = "SELECT google_address FROM google_pay WHERE user_id = $accountOwner";
$googleresult = mysqli_query($conn,$google);

$paypal = "SELECT paypal_address FROM paypal WHERE user_id = $accountOwner";
$paypalresult = mysqli_query($conn,$paypal);

$skrill = "SELECT skrill_address FROM skrill WHERE user_id = $accountOwner";
$skrillresult = mysqli_query($conn,$skrill);

$pi = "SELECT wallet_Address FROM `pi network` WHERE user_id = $accountOwner";
$piresult = mysqli_query($conn,$pi);

$momo = "SELECT user_name, account_number FROM momo WHERE user_id = $accountOwner";
$momoresult = mysqli_query($conn,$momo);

//more like if(result.num_rows > 0) in js.
if ($appleresult->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while($apple_pay = $appleresult->fetch_assoc()) {
      $Paymentmethod[] =$apple_pay;
    }
}

if ($bankresult->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while($bank_pay = $bankresult->fetch_assoc()) {
      $Paymentmethod[] =$bank_pay;
    }
}
if ($googleresult->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while($google_pay = $googleresult->fetch_assoc()) {
      $Paymentmethod[] =$google_pay;
    }
}

if ($paypalresult->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while($paypal_pay = $paypalresult->fetch_assoc()) {
      $Paymentmethod[] =$paypal_pay;
    }
}

if ($skrillresult->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while($skrill_pay = $skrillresult->fetch_assoc()) {
      $Paymentmethod[] =$skrill_pay;
    }
}

if ($piresult->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while($pi_pay = $piresult->fetch_assoc()) {
      $Paymentmethod[] =$pi_pay;
    }
}

if ($momoresult->num_rows > 0) {
    //check if the row contain the result and turn the result to an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
    while($momo_pay = $momoresult->fetch_assoc()) {
      $Paymentmethod[] =$momo_pay;
    }
}
//close the database connection
//   $conn->close();  
echo json_encode($Paymentmethod);
?>
