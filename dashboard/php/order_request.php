<?php
include('server.php');
$data = [];

function clean_Input($userInpt){
    $userInpt = trim($userInpt);
    $userInpt = strip_tags($userInpt);
    $userInpt = stripslashes($userInpt);
    $userInpt = htmlspecialchars($userInpt);
    return $userInpt;
}

$refrence = substr(md5(uniqid(rand(), true)), 0, 25); // order reference number
$UniqueRef = "SELECT COUNT(*) as count FROM transactions WHERE transaction_refrence = ?";
$stmt = $conn->prepare($UniqueRef);
$stmt->bind_param('s', $refrence);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

$_SESSION['count'] = $count;
if ($count > 0) {
    $refrence = substr(md5(uniqid(rand(), true)), 0, 25);
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $type = clean_Input($_POST['Type']);

    if ($_POST['Type'] === 'buyWallet') {
        $sellerId = clean_Input($_POST['SellerId']);
        $wallet = clean_Input($_POST['BuyerWallet']);
        $sellerWallet = clean_Input($_POST['SellerWallet']);
        $orderUnit = clean_Input($_POST['OrderUnit']);
        $OrderCost = clean_Input($_POST['Cost']);
        $receiveAmount = clean_Input($_POST['ReceiveAmount']);
        $transactionFee = clean_Input($_POST['TransactionFee']);
        $exchangeRate = $OrderCost / $orderUnit;
        $type = 'Buy';

        $stmt = $conn->prepare("SELECT {$wallet} FROM `wallet` WHERE user_id = ?");
        $stmt->bind_param('s', $_SESSION['id']);
        $stmt->execute();
        $stmt->bind_result($userBalance);
        $stmt->fetch();
        $stmt->close();

        if ($userBalance >= $OrderCost) {
            $stmt = $conn->prepare("SELECT user_id FROM `user_credentials` WHERE `user_id` = ?");
            $stmt->bind_param('s', $sellerId);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt = $conn->prepare("UPDATE wallet SET {$wallet} = {$wallet} + ? WHERE `user_id` = ?");
                $stmt->bind_param('ds', $receiveAmount, $sellerId);
                $added = $stmt->execute();

                $stmt = $conn->prepare("UPDATE wallet SET {$wallet} = {$wallet} - ? WHERE `user_id` = ?");
                $stmt->bind_param('ds', $receiveAmount, $_SESSION['id']);
                $removed = $stmt->execute();

                $stmt = $conn->prepare("UPDATE wallet SET {$sellerWallet} = {$sellerWallet} + ? WHERE `user_id` = ?");
                $stmt->bind_param('ds', $orderUnit, $_SESSION['id']);
                $added = $stmt->execute();

                $stmt = $conn->prepare("UPDATE wallet SET {$sellerWallet} = {$sellerWallet} - ? WHERE `user_id` = ?");
                $stmt->bind_param('ds', $orderUnit, $sellerId);
                $removed = $stmt->execute();

                $stmt->close();

                $stmt = $conn->prepare(
                    "INSERT INTO `p2p_transaction`(
                    `seller_id`,
                    `buyer_id`,
                    `type`,
                    `wallet-from`,
                    `wallet_to`,
                    `exchange_rate`,
                    `amount`,
                    `unit`,
                    `transaction_fee`,
                    `transaction_reference`
                    )
                    VALUES
                    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );
                $stmt->bind_param(
                    'ddsssdddds',
                    $sellerId,
                    $_SESSION['id'],
                    $type,
                    $sellerWallet,
                    $wallet,
                    $exchangeRate,
                    $receiveAmount,
                    $orderUnit,
                    $transactionFee,
                    $refrence
                );
                $result = $stmt->execute();

                if ($added && $result) {
                    $response["Successful"] = "Sent " . $receiveAmount . " " . $wallet . " to " . $sellerId;
                    echo json_encode($response);
                } else {
                    $response["Not Successful"] = "Transaction was not successful";
                    echo json_encode($response);
                }
                $stmt->close();
            } else {
                $response["user not found"] = "User not found";
                echo json_encode($response);
            }
        } else {
            $response["Insufficient Balance"] = "Insufficient Balance";
            echo json_encode($response);
        }

        echo json_encode($response);

    } elseif ($_POST['Type'] === 'sellWallet') {

        $sellerId = clean_Input($_POST['SellerId']);
        $wallet = clean_Input($_POST['BuyerWallet']);
        $sellerWallet = clean_Input($_POST['SellerWallet']);
        $orderUnit = clean_Input($_POST['OrderUnit']);
        $OrderCost = clean_Input($_POST['Cost']);
        $receiveAmount = clean_Input($_POST['ReceiveAmount']);
        $transactionFee = clean_Input($_POST['TransactionFee']);
        $exchangeRate = $OrderCost / $orderUnit;
        $type = 'Sell';

        $stmt = $conn->prepare("SELECT {$wallet} FROM `wallet` WHERE user_id = ?");
        $stmt->bind_param('s', $_SESSION['id']);
        $stmt->execute();
        $stmt->bind_result($userBalance);
        $stmt->fetch();
        $stmt->close();

        if ($userBalance >= $OrderCost) {
            $stmt = $conn->prepare("SELECT user_id FROM `user_credentials` WHERE `user_id` = ?");
            $stmt->bind_param('s', $sellerId);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt = $conn->prepare("UPDATE wallet SET {$wallet} = {$wallet} + ? WHERE `user_id` = ?");
                $stmt->bind_param('ds', $receiveAmount, $sellerId);
                $added = $stmt->execute();

                $stmt = $conn->prepare("UPDATE wallet SET {$wallet} = {$wallet} - ? WHERE `user_id` = ?");
                $stmt->bind_param('ds', $receiveAmount, $_SESSION['id']);
                $removed = $stmt->execute();

                $stmt = $conn->prepare("UPDATE wallet SET {$sellerWallet} = {$sellerWallet} + ? WHERE `user_id` = ?");
                $stmt->bind_param('ds', $orderUnit, $_SESSION['id']);
                $added = $stmt->execute();

                $stmt = $conn->prepare("UPDATE wallet SET {$sellerWallet} = {$sellerWallet} - ? WHERE `user_id` = ?");
                $stmt->bind_param('ds', $orderUnit, $sellerId);
                $removed = $stmt->execute();

                $stmt->close();

                $stmt = $conn->prepare(
                    "INSERT INTO `p2p_transaction`(
                    `seller_id`,
                    `buyer_id`,
                    `type`,
                    `wallet-from`,
                    `wallet_to`,
                    `exchange_rate`,
                    `amount`,
                    `unit`,
                    `transaction_fee`,
                    `transaction_reference`
                    )
                    VALUES
                    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );
                $stmt->bind_param(
                    'ddsssdddds',
                    $sellerId,
                    $_SESSION['id'],
                    $type,
                    $sellerWallet,
                    $wallet,
                    $exchangeRate,
                    $receiveAmount,
                    $orderUnit,
                    $transactionFee,
                    $refrence
                );
                $result = $stmt->execute();

                if ($added && $result) {
                    $response["Successful"] = "Sent " . $receiveAmount . " " . $wallet . " to " . $sellerId;
                } else {
                    $response["Not Successful"] = "Transaction was not successful";
                }
                $stmt->close();
            } else {
                $response["user not found"] = "User not found";
            }
        } else {
            $response["Insufficient Balance"] = "Insufficient Balance";
        }

        echo json_encode($response);
    }
}

echo json_encode($data);
?>
