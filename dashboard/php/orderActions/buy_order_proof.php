<?php
require('../server.php');
//1 Use the transaction ref to get the specific transaction and post its details on the page
//2 if the release fund is clicked, the transaction ref and the amount should be sent to the database so that the buyer can be credited.
//When the buyer is credited then trade should be removed from the pending to the completed.
// session_start();
$accountOwner = $_SESSION['user_id'];
$data = array();
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if ($_POST['order_refrence']) {
        $order_refrence = $_POST['order_refrence'];
        // Use prepared statements to prevent SQL injection
        $stmt = "SELECT * FROM `p2p_order` WHERE `transaction_refrence` = ? AND buyer_id = ?";
        $stmt2 = $conn->prepare($stmt);
        $stmt2->bind_param("si", $order_refrence, $accountOwner);
        $stmt2->execute();
        $result = $stmt2->get_result();
        
        if ($result->num_rows > 0) {
            // Check if the row contains the result and turn the result into an associative array ['key'=>'value'] example $database = ['name'=>'david','surname'=>'Omotoso', etc]
            while ($row = $result->fetch_assoc()) {
                $data['order'] = $row;
            }
        }
    }
    
}

echo json_encode($data);
?>
