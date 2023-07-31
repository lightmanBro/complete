<?php
require_once '../server.php';

// Retrieve the bank options from the database
$sql = "SELECT code, name FROM banks";
$stmt = $pdo->query($sql);
$banks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Send Money</title>
    <script src="https://checkout.flutterwave.com/v3.js"></script>
</head>
<body>
    <h3>Send Money</h3>
    <form id="sendMoneyForm">
        <label for="bank">Select Bank:</label>
        <select id="bank" required>
            <option value="">Select Bank</option>
            <?php foreach ($banks as $bank): ?>
                <option value="<?php echo $bank['code']; ?>"><?php echo $bank['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="accountNumber">Account Number:</label>
        <input type="text" id="accountNumber" required>
        <br>
        <label for="accountName">Account Name:</label>
        <input type="text" id="accountName" readonly>
        <br>
        <label for="amount">Amount:</label>
        <input type="text" id="amount" required>
        <br>
        <input type="submit" value="Send Money">
    </form>

    <script>
        // Function to verify the account number
        function verifyAccount(accountNumber, bankCode) {
            return new Promise((resolve, reject) => {
                FlutterwaveBank.verifyAccount({
                    account_number: accountNumber,
                    account_bank: bankCode,
                    callback: function (response) {
                        if (response.status === 'success') {
                            resolve(response.data.account_name);
                        } else {
                            reject(response.message);
                        }
                    }
                });
            });
        }

        // Handle form submission
        document.getElementById('sendMoneyForm').addEventListener('submit', async function (event) {
            event.preventDefault();

            // Retrieve the selected bank, account number, and amount values from the form
            var bankCode = document.getElementById('bank').value;
            var accountNumber = document.getElementById('accountNumber').value;
            var amount = document.getElementById('amount').value;

            try {
                // Verify the account number
                var accountName = await verifyAccount(accountNumber, bankCode);
                // Update the account name field
                document.getElementById('accountName').value = accountName;

                // Generate a unique transaction reference
                var txref = 'REF-' + Date.now();

                // Initialize Flutterwave Checkout
                FlutterwaveCheckout({
                    public_key: 'YOUR_PUBLIC_KEY',
                    tx_ref: txref,
                    amount: amount,
                    currency: 'NGN',
                    country: 'NG',
                    payment_options: 'card',
                    redirect_url: 'https://yourwebsite.com/redirect', // Replace with your redirect URL
                    customer: {
                        email: 'customer@example.com',
                        phone_number: '+2348123456789',
                        name: 'John Doe'
                    },
                    callback: function (response) {
                        // Handle the payment response
                        console.log(response);
                        if (response.status === 'successful') {
                            alert('Payment successful.');
                        } else {
                            alert('Payment failed.');
                        }
                    },
                    customizations: {
                        title: 'Send Money',
                        description: 'Payment for sending money',
                        logo: 'https://yourwebsite.com/logo.png' // Replace with your logo URL
                    }
                });
            } catch (error) {
                alert('Account verification failed. Please check the account number and try again.');
                console.error(error);
            }
        });
    </script>
</body>
</html>
