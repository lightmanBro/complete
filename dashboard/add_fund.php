<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Include the database connection file
require_once '../server.php';

// Fetch currency options from the database
$sql = "SELECT code, name FROM currencies";
$stmt = $pdo->query($sql);
$currencies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Funds</title>
</head>
<body>
    <h3>Fund Wallet</h3>
    <form id="fundWalletForm" action="initiate_payment.php" method="post">
        <label for="currency">Select Currency:</label>
        <select id="currency" name="currency">
            <?php foreach ($currencies as $currency): ?>
                <option value="<?php echo $currency['code']; ?>"><?php echo $currency['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="amount">Amount:</label>
        <input type="text" id="amount" name="amount" required>
        <br>
        <input type="submit" value="Add Funds">
    </form>

    <!-- JavaScript code -->
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script>
        // Handle form submission when the "Add Funds" button is clicked
        document.getElementById('fundWalletForm').addEventListener('submit', function(event) {
            // Stop the form from submitting
            event.preventDefault();

            // Retrieve the selected currency and amount values from the form
            var currency = document.getElementById('currency').value;
            var amount = document.getElementById('amount').value;

            // Redirect to the payment initiation page with the selected currency and amount as query parameters
            window.location.href = 'initiate_payment.php?currency=' + encodeURIComponent(currency) + '&amount=' + encodeURIComponent(amount);
        });
    </script>
</body>
</html>
