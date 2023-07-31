

<?php
// connecteď to fetchDetails.js
include('server.php');

$accountOwner = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    function clean_Input($userInpt)
    {
        $userInpt = trim($userInpt);
        $userInpt = strip_tags($userInpt);
        $userInpt = stripslashes($userInpt);
        $userInpt = htmlspecialchars($userInpt);
        return $userInpt;
    }

    $select = clean_Input($_POST['select']);
    $receiverName = clean_Input($_POST['receiver_id']);
    $sentAmount = clean_Input($_POST['amount']);

    $response = array(
        "Insufficient Balance" => "",
        "user not found" => "",
        "Successful" => ""
    );

    $stmt = $conn->prepare("SELECT {$select} FROM `wallet` WHERE user_id = ?");
    $stmt->bind_param('s', $accountOwner);
    $stmt->execute();
    $stmt->bind_result($userBalance);
    $stmt->fetch();
    $stmt->close();

    if ($userBalance >= $sentAmount) {
        $stmt = $conn->prepare("SELECT user_id FROM `user_credentials` WHERE `user_id` = ?");
        $stmt->bind_param('s', $receiverName);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $beneficiaryId = $receiverName;
            $charges = $sentAmount * 0.01;
            $toBeneficiary = $sentAmount - $charges;

            $stmt = $conn->prepare("UPDATE wallet SET {$select} = {$select} + ? WHERE `user_id` = ?");
            $stmt->bind_param('ds', $toBeneficiary, $beneficiaryId);
            $added = $stmt->execute();

            $stmt = $conn->prepare("UPDATE wallet SET {$select} = {$select} - ? WHERE `user_id` = ?");
            $stmt->bind_param('ds', $sentAmount, $accountOwner);
            $removed = $stmt->execute();

            $stmt->close();

            $refrence = substr(md5(uniqid(rand(), true)), 0, 20);
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM transactions WHERE transaction_refrence = ?");
            $stmt->bind_param('s', $refrence);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
            
            if ($count > 0) {
                $refrence = substr(md5(uniqid(rand(), true)), 0, 20);
            }

            $stmt = $conn->prepare("INSERT INTO `transactions`(`sender_id`, `receiver_id`, `currency_wallet`, `amount`, `transaction_charge`,`transaction_refrence`) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssdss', $accountOwner, $beneficiaryId, $select, $sentAmount, $charges, $refrence);
            $result = $stmt->execute();
            $stmt->close();

            if ($added && $result) {
                $response["Successful"] = "Sent " . $sentAmount . " " . $select . " to " . $receiverName;
            } else {
                $response["Not Successful"] = "Transaction was not successful";
            }
        } else {
            $response["user not found"] = "User not found";
        }
    } else {
        $response["Insufficient Balance"] = "Insufficient Balance";
    }

    echo json_encode($response);

    $conn->close();
}
?>
