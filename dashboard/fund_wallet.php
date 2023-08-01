<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}
$dataAarray = array();
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

    // Set the user_id in the session
    $_SESSION['user_id'] = $user['user_id'];

    // Continue with the rest of your code
    if ($user['verification_status']) {
        // User is logged in and account is verified
        $msg = "Login successful!";
        $username = $user['username'];
        // Fetch bank details from the virtual_account table
        $sql_bank = "SELECT * FROM virtual_account WHERE user_id = :user_id";
        $stmt_bank = $pdo->prepare($sql_bank);
        $stmt_bank->bindParam(':user_id', $user['user_id']);
        $stmt_bank->execute();

        if ($stmt_bank->rowCount() == 1) {
            $bank_details = $stmt_bank->fetch(PDO::FETCH_ASSOC);
            $bankName = $bank_details['bank_name'];
            $accountNumber = $bank_details['account_number'];
        } else {
            // Bank details not found
            $bankName = "Bank not found";
            $accountNumber = "Account not found";
        }
        // Fetch wallet amounts from the wallet table
        $sql_wallet = "SELECT * FROM wallet WHERE user_id = :user_id";
        $stmt_wallet = $pdo->prepare($sql_wallet);
        $stmt_wallet->bindParam(':user_id', $user['user_id']);
        $stmt_wallet->execute();

        if ($stmt_wallet->rowCount() > 0) {
            $wallets = $stmt_wallet->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Wallet data not found
            $wallets = array();
        }
    } else {
        // Account is not verified
        $msg = "Account is not verified.";
    }
} else {
    // User not found in the database
    $msg = "User not found.";
    // Clear the email and user_id from the session
    unset($_SESSION['email']);
    unset($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html>
<head>
<script src="https://checkout.flutterwave.com/v3.js"></script>
 <title>Wallet Page</title>
</head>
<body>
    <h1>Welcome </h1>
    <h2>Wallets:</h2>

    <?php foreach ($wallets as $wallet) { ?>
        <h3>Wallet ID: <?php echo $wallet['user_id']; ?></h3>
        <form class="currency-form" id="currency-form-<?php echo $wallet['user_id']; ?>" method="post">
            <input type="hidden" name="wallet_id" value="<?php echo $wallet['ind']; ?>">
            <label for="currency">Select Currency:</label>
            <select name="currency">
                <option value="None">Select</option>
                <option value="Naira">Naira</option>
                <option value="Rand">Rand</option>
                <option value="Cedi">Cedi</option>
                <option value="Dollar">Dollar</option>
                <!-- Add more currencies here if needed -->
            </select>
            <br>

            <!-- Naira form (hidden by default) -->
            <div class="naira-details" style="display: none;">
                <p>Fund your naira wallet through either Bank transfer or Card:</p>
                <button type="button" id="bank-transfer-btn">Bank Transfer</button>
                <button type="button" id="card-btn" data-selected="false">Card</button>

                <!-- Account details from the virtual account table (hidden by default) -->
                <div class="account-details" style="display: none;">
                    <h3>Bank Name: <span><?php echo $bankName; ?></span></h3>
                    <div class="account-number">
                        Account Number: <?php echo $accountNumber; ?>
                    </div>
                </div>

                <!-- Card form (hidden by default) -->
                <div class="card-form" style="display: none;">
    <h1>Add Funds using Card</h1>
    <form id="paymentForm" action="" method="POST">
    <label for="name">Name:</label>
<input type="text" id="name" name="name" value="<?php echo $user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name']; ?>" required hidden>

    <input type="email" name="email" id="emailInput" value="<?php echo $email; ?>" placeholder="Email" class="box" style="display: none;" disabled required>
    <label for="amount">Amount:</label>
    <input type="number" id="amount" name="amount" step="0.01" required>

    <button type="button" onclick="payWithFlutterwave()">Pay Now</button>
  </form>
</div>
            </div>
        </form>
        <hr>
    <?php } ?>
    
    <script>
    function payWithFlutterwave() {
        const name = document.getElementById('name').value;
console.log(name); // This will log the full name value to the console


      const emailInput = document.getElementById('emailInput');
const email = emailInput.value;
console.log(email); // This will log the email value to the console

      const amount = document.getElementById('amount').value;

      FlutterwaveCheckout({
        public_key: 'FLWPUBK-5a5793248416bc75092d47928552f417-X',
        tx_ref: Date.now(),
        amount: amount,
        currency: 'USD', // Change to your desired currency code
        payment_type: 'card',
        customer: {
          email: email,
          name: name,
        },
        callback: function(response) {
          console.log(response);

          async function sendDataToPhp(url){
                const formData = new FormData();
                formData.append('amount',response.amount);
                formData.append('email',response.customer.email);
                formData.append('transaction_id',response.transaction_id);
                formData.append('tx_ref',response.tx_ref);
                formData.append('status',response.status);
                formData.append('created_at',response.created_at);
                formData.append('flutterwave_ref',response.flw_ref);
                let urlEncodedData = new URLSearchParams(formData).toString()
                    console.log(urlEncodedData)
                try {
                    let respo = await fetch(url,{
                    method:'POST',
                    body:formData
                    })
                    if(respo.ok){
                        let data = await respo.json();
                        console.log(data);
                        console.log('response received')
                    }
                } catch (error) {
                    console.log(error);
                }  
            }
            //sending the data to the database;
            sendDataToPhp('../webhook.php');

          if (response.status === 'successful') {
            // document.getElementById('paymentForm').submit();
          } else {
            // Handle payment failure
            alert('Payment failed. Please try again.');
          }
        },
      });
    }
  </script>
    <script>
        // Function to show and hide form elements based on user's selection
        function showHideElements(selectedOption, walletId) {
            const nairaDetailsDiv = document.querySelector("#currency-form-" + walletId + " .naira-details");
            const bankTransferBtn = document.querySelector("#currency-form-" + walletId + " #bank-transfer-btn");
            const cardBtn = document.querySelector("#currency-form-" + walletId + " #card-btn");
            const accountDetailsDiv = document.querySelector("#currency-form-" + walletId + " .account-details");
            const cardFormDiv = document.querySelector("#currency-form-" + walletId + " .card-form");

            if (selectedOption === "Naira") {
                nairaDetailsDiv.style.display = "block";
                bankTransferBtn.style.display = "block";
                cardBtn.style.display = "block";

                // Show the card form only if the user explicitly clicks on the "Add Card" button
                if (cardBtn.getAttribute("data-selected") === "true") {
                    cardFormDiv.style.display = "block";
                } else {
                    cardFormDiv.style.display = "none";
                }

                accountDetailsDiv.style.display = "none";
            } else {
                nairaDetailsDiv.style.display = "none";
                bankTransferBtn.style.display = "none";
                cardBtn.style.display = "none";
                accountDetailsDiv.style.display = "none";
                cardFormDiv.style.display = "none";
            }
        }

        // Add event listeners to the currency selection dropdowns
        const currencyForms = document.querySelectorAll(".currency-form");
        currencyForms.forEach((form) => {
            const walletId = form.id.split("-")[2]; // Extract the wallet ID from the form ID
            form.addEventListener("change", function() {
                const selectedOption = this.querySelector("select[name='currency']").value;
                showHideElements(selectedOption, walletId);
            });
        });

        // Add event listeners to the Bank Transfer and Card buttons
        const bankTransferBtns = document.querySelectorAll("#bank-transfer-btn");
        bankTransferBtns.forEach((btn) => {
            btn.addEventListener("click", function() {
                const walletId = this.closest(".currency-form").id.split("-")[2];
                const accountDetailsDiv = document.querySelector("#currency-form-" + walletId + " .account-details");
                const cardFormDiv = document.querySelector("#currency-form-" + walletId + " .card-form");
                accountDetailsDiv.style.display = "block";
                cardFormDiv.style.display = "none";
            });
        });

        const cardBtns = document.querySelectorAll("#card-btn");
        cardBtns.forEach((btn) => {
            btn.addEventListener("click", function() {
                const walletId = this.closest(".currency-form").id.split("-")[2];
                const accountDetailsDiv = document.querySelector("#currency-form-" + walletId + " .account-details");
                const cardFormDiv = document.querySelector("#currency-form-" + walletId + " .card-form");
                accountDetailsDiv.style.display = "none";
                cardFormDiv.style.display = "block";

                // Set the "data-selected" attribute to true to remember the user's choice
                this.setAttribute("data-selected", "true");
            });
        });

        // Function to automatically insert forward slash in the expiry date input
        function insertSlash(input) {
            const value = input.value;
            if (value.length === 2 && !value.includes('/')) {
                input.value = value + '/';
            }
        }

        // Add event listener to the expiry date input
        const expiryDateInput = document.getElementById('expiryDate');
        expiryDateInput.addEventListener('input', function () {
            insertSlash(this);
        });

        // Function to restrict the CVV input to three digits
        function restrictCVVInput(input) {
            const value = input.value;
            const maxLength = input.getAttribute('maxlength');
            if (value.length > maxLength) {
                input.value = value.slice(0, maxLength);
            }
        }

        // Add event listener to the CVV input
        const cvvInput = document.getElementById('cvv');
        cvvInput.addEventListener('input', function() {
            restrictCVVInput(this);
        });
    </script>
</body>
</html>
