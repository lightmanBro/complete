<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

// Get the email from the session

$email = $_SESSION['email'];

// Include the database connection file
require_once '../server.php';

// Retrieve the user data from the database
$sql = "SELECT * FROM user_credentials WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();

if ($stmt->rowCount() == 1) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $email = $user['email'];

    // Continue with the rest of your code
    if ($user['verification_status']) {
        // User is logged in and account is verified
        $msg = "Login successful!";
        $username = $user['username'];
        $accountNumber = $user['account_number'];
    } else {
        // Account is not verified
        $msg = "Account is not verified.";
    }
} else {
    // User not found in the database
    $msg = "User not found.";
    // Clear the email from the session
    unset($_SESSION['email']);
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="fontsawesome/css/all.css">
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="./style/notifications.css">
    <link rel="stylesheet" href="./style/addmoney.css">
    <link rel="stylesheet" href="./style/sendmoney.css">
    <link rel="stylesheet" href="./mobile.css">
    <title>Tamopie Home</title>
</head>

<body>
    <div class="container" id="big-container">

        <aside class="" id="menu">
            <i class="b fa-regular fa-circle-xmark" id="closemenu"></i>
            <div class="top">

                <div class="logo">
                    <h2><a href="">
                            <img src="./img/colorLogo.png" alt="">
                        </a></h2>
                </div>
            </div>

            <div class="sidebar">

                <!-- <i class="fa-regular fa-circle-xmark" id="closemenu"></i> -->
                <a href="" class="active">
                    <span class="b material-icons-sharp">
                        grid_view
                    </span>
                    <h3 class="b">Dashboard</h3>

                </a>
                <a href=""><span class="b material-icons-sharp">
                        account_balance
                    </span>
                    <h3 class="b">Banks</h3>
                    </span>
                </a>
                <a href="./transactionHistory/transaction-history.html">
                    <span class=" b material-icons-sharp">
                        swap_horiz
                    </span>
                    <h3 class="b">Orders</h3>
                    </span>
                </a>
                <a href="" id="notification"><span class="b material-icons-sharp">
                        notifications_none
                    </span>
                    <h3 class="b">Notifications</h3>
                    <div class="circle">
                        <h3 class="white">0</h3>
                    </div>
                    </span>
                </a>
                <a href="./history/transaction-history.html"><span class="b material-icons-sharp">
                        history
                    </span>
                    <h3 class="b">History</h3>
                    </span>
                </a>
                <a href="../dashboard/settings/"><span class="b material-icons-sharp">
                        tune
                    </span>
                    <h3 class="b">Settings</h3>
                    </span>
                </a>
                <a href="../logout.php"><span class="b material-icons-sharp">
                        power_settings_new
                    </span>
                    <h3 class="b">Log out</h3>
                    </span>
                </a>
            </div>
        </aside>
        <!-- MAIN -->
        <main>
            <div class="firstheading">

                <div class="menu-show">
                    <i class="b fa-sharp fa-solid fa-bars " id="menu-btn"></i>
                    <div class="logo" id="logo">
                        <h2><a href="../index.html">
                                <img src="./img/colorLogo.png" alt="">
                            </a></h2>
                    </div>
                </div>
                <h2 class="h2 hr">Olatunde Badiru Ekehinde</h2>
                <div class="profile">
                    <img src="./img/COB-BOB-IRT-enroll_tractor.jpg" alt="">
                    <div id="id">USER-ID: <span></span></div>
                </div>
            </div>
            <div class="heading">
                <div class="insights">
                    <div class="stat">
                        <div class="balance">
                            <div class="left">
                                <h3>Total<span class="material-icons-sharp">
                                        visibility
                                    </span></h3>
                                <h2><span id="cur-logo"></span><span id="total"> </span></h2>
                                <div class=""></div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- <div class="dsahboard" id="dashboard">


                </div> -->

                <div class=" off-back off">
                    <a href="">
                        <span class="expand material-icons-sharp">
                            expand_more
                        </span>
                        <!-- <h3>more</h3> -->
                    </a>

                </div>
            </div>



            <!-- HEADING TWO                                  -->
            <div class="heading minus none" id="currency-overflow">
                <div class="insights">
                    <div class="stat">
                        <!-- <span class="circle material-icons-sharp">
                            account_balance_wallet
                        </span> -->
                        <div class="balance">
                            <div class="left">
                                <h3>Yuen<span class="material-icons-sharp">
                                        visibility
                                    </span></h3>
                                <h2><span>¥</span> 35,000</h2>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="insights">

                    <div class="stat">
                        <!-- <span class="circle material-icons-sharp">
                            account_balance_wallet
                        </span> -->
                        <div class="balance">
                            <div class="left">
                                <h3>Leon <span class="material-icons-sharp">
                                        visibility
                                    </span></h3>
                                <h2><span>؋</span> 15,000
                                </h2>

                            </div>
                        </div>

                    </div>
                </div>



                <div class="insights">
                    <div class="stat">
                        <!-- <span class="circle material-icons-sharp">
                            account_balance_wallet
                        </span> -->
                        <div class="balance">
                            <div class="left">
                                <h3>Pounds <span class="material-icons-sharp">
                                        visibility
                                    </span></h3>
                                <h2><span>£</span> 1,467</h2>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="insights">
                    <div class="stat">
                        <!-- <span class="circle material-icons-sharp">
                            account_balance_wallet
                        </span> -->
                        <div class="balance">
                            <div class="left">
                                <h3>Burun <span class="material-icons-sharp">
                                        visibility
                                    </span></h3>
                                <h2><span>฿</span> 3,000</h2>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="insights">
                    <div class="stat">
                        <!-- <span class="circle material-icons-sharp">
                            account_balance_wallet
                        </span> -->
                        <div class="balance">
                            <div class="left">
                                <h3>Deran<span class="material-icons-sharp">
                                        visibility
                                    </span></h3>
                                <h2><span>₫</span> 3,000</h2>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="insights">
                    <div class="stat">
                        <!-- <span class="circle material-icons-sharp">
                            account_balance_wallet
                        </span> -->
                        <div class="balance">
                            <div class="left">
                                <h3>Cedi Balance <span class="material-icons-sharp">
                                        visibility
                                    </span></h3>
                                <h2><span>N</span> 3,000</h2>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="insights">
                    <div class="stat">
                        <!-- <span class="circle material-icons-sharp">
                            account_balance_wallet
                        </span> -->
                        <div class="balance">
                            <div class="left">
                                <h3>Cedi Balance <span class="material-icons-sharp">
                                        visibility
                                    </span></h3>
                                <h2><span>N</span> 3,000</h2>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="insights">
                    <div class="stat">
                        <!-- <span class="circle material-icons-sharp">
                            account_balance_wallet
                        </span> -->
                        <div class="balance">
                            <div class="left">
                                <h3>Cedi Balance <span class="material-icons-sharp">
                                        visibility
                                    </span></h3>
                                <h2><span>N</span> 3,000</h2>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="insights">
                    <div class="stat">
                        <!-- <span class="circle material-icons-sharp">
                            account_balance_wallet
                        </span> -->
                        <div class="balance">
                            <div class="left">
                                <h3>Cedi Balance <span class="material-icons-sharp">
                                        visibility
                                    </span></h3>
                                <h2><span>N</span> 3,000</h2>

                            </div>
                        </div>

                    </div>
                </div>
                

            </div>

            <div class="dashboard">
                <div>
                    <a href="" class=" insights-off green" id="add-btn">
                        <div>
                            <div class=" dash stat">
                                Add
                            </div>
                            <span class="material-icons-sharp">
                                add
                            </span>

                        </div>
                    </a>

                </div>

                <div>
                    <a href="" class="insights-off orange" id="send-btn">
                        <div>

                            <div class="dash stat">
                                Send
                            </div>
                            <span class="material-icons-sharp">
                                send
                            </span>
                        </div>
                    </a>
                </div>

                <a href="trade/trade.html" class="insights-off blue" id="pair-to-pair">
                    <div>
                        <div class="dash stat">
                            p2p
                        </div>
                    </div>
                </a>
            </div>

            <div class=" available ">
                <h3 class="available-h2">Available Currencies</h3>
                <div class="available-heading">
                    <table>
                        <!-- headings -->
                        <tr>
                            <th>Country</th>
                            <th>Currency</th>
                            <th>To 1 Usd</th>
                        </tr>
                        <tr>
                            <td>Germany</td>
                            <td>£</td>
                            <td>0.8</td>
                        </tr>
                        <tr>
                            <td>Tonga</td>
                            <td>₸</td>
                            <td>52</td>
                        </tr>
                        <tr>
                            <td>Seychel</td>
                            <td>SeY : ₴</td>
                            <td>0.55</td>
                        </tr>
                        <tr>
                            <td>Cameroon</td>
                            <td>Cefa : ⨎</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Laughing Bacchus Winecellars</td>
                            <td>Yoshi Tannamuri</td>
                            <td>Canada</td>
                        </tr>
                        <tr>
                            <td>Magazzini Alimentari Riuniti</td>
                            <td>Giovanni Rovelli</td>
                            <td>Italy</td>
                        </tr>
                    </table>
                    <div class="more">
                        <!-- <a href="">
                            <span class="circle material-icons-sharp">
                                expand_more
                            </span>
                        </a> -->
                    </div>
                </div>
            </div>


            <div class="notifications-modal" id="notifications-modal">
                <div class="sticky">
                    <div class="close">X</div>
                    <hr>
                </div>
                <div class="items-box">
                    <center>Notifications</center>
                </div>
                <a href="" hidden>
                    <div class="notifications">
                        <form id="buy-trade-notification">
                            <input type="text" name="trade_type" value="buy" hidden>
                            <span class="type">Buy</span>
    
                            <input type="text" name="wallet" value="" hidden>
                            <span class="wallet">Dollar</span>
    
                            <input type="text" name="unit_amount" value="" hidden>
                            <span class="unit-amount">76</span>
    
                            <input type="text" name="cost" value="" hidden>
                            <span class="cost">46000</span>
    
                            <button type="reset" class="buy-reset">Reject</button>
                            
                            <button type="submit" class="buy-submit">Release</button>
                        </form>
                    </div>
                </a>

                <!-- <div class="notifications">
                    <form id="sell-trade-notification">
                        <input type="text" name="trade_type" value="sell" hidden>
                        <span class="type">Sell</span>

                        <input type="text" name="wallet" value="" hidden>
                        <span class="wallet">Dollar</span>

                        <input type="text" name="unit_amount" value="" hidden>
                        <span class="unit-amount">76</span>

                        <input type="text" name="cost" value="" hidden>
                        <span class="cost">46000</span>

                        <button type="reset" class="sell-reset">Reject</button>
                        <button type="submit" class="sell-submit">Release</button>
                    </form>
                </div> -->
            </div>
        </main>


        <!-- Modals -->
        <div class="add-money-modal none" id="add-money-modal">
            <div class="blank"></div>

            <div class="add-container">
                <div class="add-container-top">
                    <!-- <div class="back-link">
                        <a href="">Add</a>
                    </div> -->
                    <div class="close-btn">
                        <span class="material-icons-sharp">
                            close
                        </span>
                    </div>
                    Add to wallet
                </div>
                <div class="add-container-body">
                    <div class="add-container-body-top">
                        <div class="fund-wallet">
                            <h2>Fund <span></span> Wallet</h2>
                            <p>To fund wallet provide the datails below</p>
                        </div>
                        <div class="select">
                            Select Wallet
                            <select name="" id="select">
                                <option value="select-wallet">Select Wallet</option>
                                <option value="Naira">Naira Wallet</option>
                                <option value="Cedi">Cedi wallet</option>
                                <option value="Dollar">Dollar Wallet</option>
                                <option value="Yuen">Yuen wallet</option>
                            </select>
                        </div>
                    </div>
                    <div class="banks none">
                        <!-- select button to choose wether the transaction will be done by bank transfer or card deposit -->
                        <!-- if transaction will be done by bank then show bank information entry modal-->

                        <p>Fund <span>Naira</span> wallet via Bank transfer</p>
                        <div class="wallet-balance">
                            <div class="cards amount">
                                <!-- Bank details and etc -->
                                <!-- if wallet selected is nigeria, it should only display the account number -->
                                <!-- if wallet selected is not nigeria it should display card options -->
                                <h3>Bank Name :<span>UBA Bank</span></h3>
                                <div class="account-number">
                                    Account Number: 234-5642-8796
                                </div>
                            </div>
                        </div>
                        <div class="wallet-balance">
                            <!-- Bank account informations -->
                            <div class="cards amount">
                                <!-- Bank details and etc -->
                                <!-- if wallet selected is nigeria, it should only display the account number -->
                                <!-- if wallet selected is not nigeria it should display card options -->
                                <h3>Bank Name :<span>Access Bank</span></h3>
                                <div class="account-number">
                                    Account Number: 234-5642-8796
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="buttons" id="buttons">
                        <button type="submit" class="form-btn bank-transfer">Bank Transfer</button>
                        <button type="submit" class="form-btn">p2p</button>
                    </div>



                    <!-- Fund via p2p options too will be here -->
                    <div class="deposits none">
                        <div class="d-top">
                            <p>Choose a Payment gateway for <span>Cedi</span></p>
                        </div>
                        <form action="" class="forms">
                            <div class="cards amount">
                                <button type="radio"></button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <div id="send-money-modal">
            <div class="blank"></div>
            <div class="send-container">
                <div class="send-container-top">
                    <!-- <div class="back-link">
                        <a href="">Send</a>
                    </div> -->
                    <div class="send-close-btn">
                        <span class="material-icons-sharp">
                            close
                        </span>
                    </div>
                    Send from wallet
                </div>
                <div class="w-to-w">
                    <div class="add-container-body-top">
                        <div class="fund-wallet">
                            <h2></h2>
                            <p>To fund wallet choose available options</p>
                        </div>

                    </div>

                </div>
                <div class="buttons">
                    <button type="submit" class="send-form-btn wallet-to-wallet">Send to wallet</button>
                    <button type="submit" class="send-form-btn wallet-to-bank">Send to bank</button>
                </div>




                <!-- Wallet to wallet -->
                <form id="p2p-form">
                    <div class="select " id="wallet">
                        <label for="select">
                            Select Wallet
                            <select class="wallet-select" id="send-select">
                                <option value="" id="available_curr">Select Wallet</option>
                                <!-- The available currency from database will be displayed here -->
                            </select>
                        </label>

                        <div class="add-container-body-top">
                            <div class="fund-wallet">
                                <div class="w">
                                    <p><span> </span></p>
                                    <!-- this is where the current selected wallet value will be displayed -->
                                </div>
                                <!-- check if the wallet balance is grater than the entered amount
                                if yes, then continue to the transaction if no, then return a message of insufficient balance -->
                            </div>
                        </div>
                        <div class="send-inputs">
                            <label for="beneficiary-name">
                                User Id
                                <input type="text" class="bottom-border" id="user_name">
                            </label>

                            <label for="user-amount">
                                Enter Amount
                                <input type="text" class=" bottom-border" maxlength="8"
                                    id="amount_to_send">
                            </label>
                        </div>
                        <div class="confirm-details">
                            <p>Confirm Receiver</p>
                            <div class="details">
                                <p class="name"></p>
                            </div>

                        </div>
                        
                        <!-- if confirm button is clicked, check if the entered username is inside the database
                         then open a modal to enter password for the user to confirm the transaction. -->
                        <button type="submit" class="form-btn" id="confirm">Confirm</button>
                        <button type="submit" class="form-btn" id="form_btn">Send</button>
                </form>
            </div>



            <div class="deposits" id="bank-deposit">
                <div class="add-container-body-top">
                    <div class="fund-wallet">
                        <div class="w">
                            <p><span> </span></p>

                        </div>
                        <h2></h2>
                        <p>To send money from wallet to bank account</p>
                        <!-- open a wallet select options,
                            show a bank transfer modal box where bank can be selected, account number entered,
                            amount entered, narration entered, confirmation screen then the procced btn click, 
                            account password to confirm transaction -->
                        <label for="select">
                            Select Wallet
                            <div class="sel-cont">
                                <select class="to-bank" id="send-to-bank">
                                    <option value="select-wallet">Select Wallet</option>
                                    <option value="Naira">Naira Wallet</option>
                                    <option value="Dollar">Dollar wallet</option>
                                    <option value="Pounds">Pounds Wallet</option>
                                    <option value="Euro">Euro Wallet</option>
                                    <option value="Yuen">Yuen wallet</option>
                                </select>
                            </div>
                        </label>
                    </div>
                </div>
                <!-- <div class="d-top">
                        <p>Amount to deposit</p>
                    </div> -->

                <!-- Paymeny gateway lists -->
                <!-- Bank details and etc -->
                <!-- if wallet selected is nigeria, it should only display the account number -->
                <!-- if wallet selected is not nigeria it should display card options -->


                <div id="forms-to-bank">

                    <form id="naira-form" method="post">
                        <div class="to-b">
                            <label for="e">Bank Name
                                <div class="inpt">
                                    <input type="text" id="bank-name" class=" bottom-border" name="bank-name"
                                        placeholder="Select Bank">
                                </div>
                            </label>
                            <label for="e">Account Number
                                <div class="inpt">
                                    <input type="text" id="acc-num" class=" bottom-border" name="acc-num"
                                        placeholder="Account Number">
                                </div>
                            </label>
                            <label for="e">Amount
                                <div class="inpt">
                                    <input type="text" id="sentVal" class=" bottom-border" name="sentVal"
                                        placeholder="minimum 100">
                                </div>
                            </label>

                            <label for="e">Narration
                                <div class="inpt">
                                    <input type="text" id="trans-narr" name="trans-narr"
                                        placeholder="e.g for school fee">
                                </div>
                            </label>
                        </div>
                        <!-- the id for the button is going to be for the naira account -->
                        <button type="submit" class=" form-btn send-form-btn wallet-to-bank"
                            id="naira-confirm-send">Confirm</button>
                    </form>
                </div>



                <div class="dollar-container" id="dollar-modal">

                    <form action="" id="dollar-form" method="post">

                        <div class="to-b">
                            <label for="e">Bank Name
                                <div class="inpt">
                                    <input type="text" id="bank-name" placeholder="Select Bank">
                                </div>
                            </label>
                            <label for="e">IBAN/Account Number
                                <div class="inpt">
                                    <input type="text" id="acc-num" placeholder="Account Number">
                                </div>
                            </label>
                            <label for="e">Account name
                                <div class="inpt">
                                    <input type="text" id="acc-name" placeholder="minimum 100">
                                </div>
                            </label>
                            <label for="e">Account type
                                <!-- dropdown menu -->
                                <div class="inpt">
                                    <input type="text" id="acc-name" placeholder="minimum 100">
                                </div>
                            </label>
                            <label for="e">Receipient's email
                                <div class="inpt">
                                    <input type="text" id="acc-name" placeholder="minimum 100">
                                </div>
                            </label>
                            <label for="e">Receipient's address
                                <div class="inpt">
                                    <input type="text" id="acc-name" placeholder="minimum 100">
                                </div>
                            </label>
                            <label for="e">Receipient's Postal code
                                <div class="inpt">
                                    <input type="text" id="acc-name" placeholder="minimum 100">
                                </div>
                            </label>

                            <label for="e">Bank routing number/Sort code
                                <div class="inpt">
                                    <input type="text" id="trans-narr" placeholder="e.g for school fee">
                                </div>
                            </label>
                            <label for="e">Swift code
                                <div class="inpt">
                                    <input type="text" id="sentVal" placeholder="minimum 100">
                                </div>
                            </label>

                            <label for="e">Description
                                <div class="inpt">
                                    <input type="text" id="trans-narr" placeholder="e.g for school fee">
                                </div>
                            </label>
                        </div>
                        <button type="submit" class="send-form-btn wallet-to-bank" id="confirm-send">Confirm</button>
                    </form>


                </div>
            </div>
        </div>

    </div>
    </div>
    <script src="./scripts/fetchDetails.js"></script>
    <script src="./scripts/Home.js"></script>
    <script src="./scripts/wallet-balance.js"></script>
    <script src="./notifications.js"></script>
    <script src="./userSettings.js"></script>
    <script src="./script.js"></script>
    <script src="./scripts/sendToDb.js"></script>
</body>

</html>