<?php
include('server.php');
// print_r($_POST);
// session_start();
$accountOwner = $_SESSION['user_id'];
$data = [];
$user_id=0;

    // if($order){
    //     $response['succesfull']= "Order succesfull";
    //     $data['successfull']= $response;
    // }

    
    /* when the order is placed the amount of the order placed will be removed from their total account and saved in p2p account
    and as buyers requests to purchase chunks of the trade and their trade is verified and approved then that amount will be removed from the 
    user balance immediately and added to the buyer account. the new account will be updated on the trade table and will be sent to the p2p table as the new highest rate. */

    /*will retrieve all order data from the table where this particular user is trying to buy and send it to the history page as an active ordre*/

    /*will also retrieve data from the table where this particular user is trying to sell then send it to the history page for approval if 
    another user requests to buy it*/
$dataO = "SELECT * FROM `p2p_buy_order` WHERE `seller_id` = $accountOwner";
$orderData = mysqli_query($conn,$dataO);
    if ($orderData->num_rows > 0) {
        while($orderD = $orderData->fetch_assoc()) {
            $dataRes[]= $orderD;
            $data['order_data'] = $dataRes;
        
        }
      }

echo json_encode($data);
// print_r($orderData)
?>