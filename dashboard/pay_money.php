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
<div class="fund-wallet">
<label for="currency">Select Currency:</label>
    <select name="currency" id="currency">
        <option value="None">Select</option>    
        <option value="NGN">NGN (Naira)</option>
        <option value="ZAR">ZAR (Rand)</option>
        <option value="GHS">GHS (Cedi)</option>
        <option value="USD">USD (Dollar)</option>
        </select>
        <form id="paymentForm">
    <input type="text" id="name" name="name" value="<?php echo $user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name']; ?>" required hidden>

    <input type="email" name="email" id="emailInput" value="<?php echo $email; ?>" placeholder="Email" class="box" style="display: none;" disabled required>

    <label for="amount">Amount:</label>
    <input type="number" id="amount" name="amount" step="0.01" required>
    <br>
    <label for="paymentMethod">Select Payment Method:</label>
    <select name="paymentMethod" id="paymentMethod">
        <option value="card">Card</option>
        <option value="bank_transfer">Bank Transfer</option>
        <!-- Add more payment method options here if needed -->
    </select>
    <button type="button" onclick="payWithFlutterwave()">Pay Now</button>
</form>
</div>

<script>
  async function payWithFlutterwave() {
    const currency = document.getElementById('currency').value;
    const name = document.getElementById('name').value;
    const emailInput = document.getElementById('emailInput');
    const email = emailInput.value;
    const amount = document.getElementById('amount').value;
    const paymentMethod = document.getElementById('paymentMethod').value;

    // Replace with the actual sub-account ID for the Ghanaian merchant
    const ghanaianSubAccountID = 'RS_93A21110ADCE696B196F295488BDAF92';

    // Check if the selected currency is GHS (Ghanaian Cedi)
    if (currency === 'GHS') {
      try {
        const response = await axios.post('https://api.flutterwave.com/v3/payments', {
          tx_ref: Date.now().toString(),
          amount: amount,
          currency: 'GHS',
          payment_type: paymentMethod,
          customer: {
            email: email,
            name: name,
          },
          public_key: 'FLWPUBK-9833fff9d279b3ce1c3bb79f885248d6-X', // Replace with your actual Flutterwave API public key
          subaccounts: [
            {
              id: ghanaianSubAccountID,
              transaction_charge_type: 'flat_subaccount',
              transaction_charge: 0,
            },
          ],
        }, {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer FLWPUBK-9833fff9d279b3ce1c3bb79f885248d6-X', // Replace with your actual Flutterwave API public key
          },
        });

        // Check if the payment was initiated successfully
        if (response.data.status === 'success') {
          // Redirect user to the payment page
          window.location.href = response.data.data.link;
        } else {
          // Handle payment initiation failure
          alert('Payment initiation failed. Please try again later.');
        }
      } catch (error) {
        // Handle API request error
        console.error('Error:', error);
        alert('An error occurred. Please try again later.');
      }
    } else {
      // For currencies other than GHS, use the regular API for Nigerian transactions
      // Implement the regularAPICallForNigeria function here if needed
      alert('Invalid currency selection.');
    }
  }
</script>
</body>
</html>
