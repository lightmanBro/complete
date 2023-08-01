<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

// Include the database connection file
require_once '../server.php';

// Retrieve the user data from the database
$sql = "SELECT * FROM user_credentials WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':email', $_SESSION['email']);
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

        // Fetch wallet amounts from the wallet table
        $sql_wallet = "SELECT * FROM wallet WHERE user_id = :user_id";
        $stmt_wallet = $pdo->prepare($sql_wallet);
        $stmt_wallet->bindParam(':user_id', $user['user_id']);
        $stmt_wallet->execute();

        if ($stmt_wallet->rowCount() > 0) {
            $wallets = $stmt_wallet->fetch(PDO::FETCH_ASSOC);
        } else {
            // Wallet data not found
            $wallets = array();
        }
    } else {
        // Account is not verified
        $msg = "Account is not verified.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wallet</title>
    <style>
        /* Styling for the container holding the select option and balance box */
        #container {
            display: flex;
            align-items: center;
        }

        /* Styling for the balance box */
        #balanceContainer {
            background-color: #fff;
            padding: 8px;
            border: none;
            border-radius: 4px;
            margin-left: 10px;
        }
        /* Styling for the input box container */
        #amountContainer {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 8px;
            width: 200px;
            margin-top: 10px; /* Add margin to push it down below the select */
        }

        /* Styling for the input box */
        #amountInput {
            flex: 1;
            border: none;
            outline: none;
        }

        /* Styling for the label */
        #amountLabel {
            margin-right: 8px;
        }
        .loading-spinner {
      display: inline-block;
      width: 20px;
      height: 20px;
      border: 3px solid rgba(0, 0, 0, 0.3);
      border-radius: 50%;
      border-top: 3px solid #3498db;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    #bankFormContainer {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2>Wallet Transfer</h2>
    
    <div id="currencyForm">
  <!-- Move the "Choose balance to transfer from" text above the select element -->
  <p>Choose balance to transfer from:</p>
  <div id="container">
    <select id="currencySelect" required>
      <option value="none">Select Wallet</option>
      <option value="Naira">NGN</option>
      <option value="Cedi">GHS</option>
      <option value="Dollar">USD</option>
      <option value="Rand">ZAR</option>
      <option value="Pounds">GPB</option>
    </select>
    <!-- Separate div to display balance -->
    <div id="balanceContainer"></div>
  </div>
</div>

<!-- Input box for the amount -->
<form id="nairaForm" action="nairatransfer.php" method="POST">
  <p id="amountLabel">How much do you want to send?</p>
  <div id="amountContainer"> 
    <input type="number" step="0.01" min="0.01" name="amount" id="amountInput" placeholder="Enter amount" required><br><br>
  </div>
  <br>
  <div id="verificationForm">
    <label for="bank">Bank:</label>
    <br>
    <select id="bank" name="bank" required>
      <option value="">Select a Bank</option>
      <!-- The bank options will be populated dynamically using JavaScript -->
    </select>
    <br><br>
    <label for="accountNumber">Account Number:</label>
    <div>
      <input type="text" id="accountNumber" name="accountNumber" minlength="10" maxlength="10" required>
      <span id="loadingSpinner" class="loading-spinner" style="display: none;"></span>
    </div>
    <br>
    <label for="accountName">Account Name:</label>
    <br>
    <input type="text" id="accountName" name="accountName" readonly>
    <br><br>
    <button type="submit">Send</button>
  </div>
</form>

<!-- Ghana Transfer Section -->
<div id="ghanaForm" style="display: none;">
<form action="submit_ghana_transfer.php" method="POST">
  <label for="amount">How much do you want to send?</label>
  <br>
  <input type="number" step="0.01" min="0.01" name="amount" id="amountInputGHS" placeholder="Enter amount" required><br><br>

  <label for="network">Network</label>
  <select name="network" id="networkGHS" required>
    <!-- List of supported Ghana mobile money networks -->
    <option value="MTN">MTN</option>
    <option value="AirtelTigo">AirtelTigo</option>
    <option value="Vodafone">Vodafone</option>
  </select><br><br>

  <label for="mobile_number">Mobile Number</label>
  <input type="tel" name="mobile_number" id="mobile_numberGHS" required pattern="(\+233|0)\d{9}"><br><br>

  <label for="account_name">Account Name</label>
  <input type="text" name="account_name" id="account_nameGHS" required><br><br>

  <!-- New Email Field -->
  <label for="email">Email</label>
  <input type="email" name="email" id="emailGHS" required><br><br>

  <label for="narration">Narration</label>
  <input type="text" name="narration" id="narrationGHS"><br><br>

  <button type="submit">Send</button>
</form>

</div>
<div id="usdForm" style="display: none;">
  <form action="/submit_usd_transfer" method="POST">
    <label for="amount">How much do you want to send?</label>
    <br>
    <input type="number" step="0.01" min="0.01" name="amount" id="amountInputUSD" placeholder="Enter amount" required><br><br>

    <label for="recipient_phone">Recipient's phone number (use +234 format)</label>
    <br>
    <input type="tel" name="recipient_phone" id="recipientPhoneInput" pattern="(\+234)\d{10}" required><br><br>

    <label for="bank_name">Bank name</label>
    <br>
    <input type="text" name="bank_name" id="bankNameInput" required><br><br>

    <label for="account_number">IBAN/Account number</label>
    <br>
    <input type="text" name="account_number" id="accountNumberInput" required><br><br>

    <label for="account_name">Account name</label>
    <br>
    <input type="text" name="account_name" id="accountNameInput" placeholder="Firstname Lastname" required><br><br>

    <label for="account_type">Account type</label>
    <br>
    <select name="account_type" id="accountTypeSelect" required>
      <option value="">Select Account Type</option>
      <option value="Depository">Depository</option>
      <option value="Checking">Checking</option>
    </select><br><br>

    <label for="recipient_email">Recipient's email</label>
    <br>
    <input type="email" name="recipient_email" id="recipientEmailInput" required><br><br>

    <label for="recipient_address">Recipient's address</label>
    <br>
    <input type="text" name="recipient_address" id="recipientAddressInput" required><br><br>

    <label for="recipient_city">Recipient's city</label>
    <br>
    <input type="text" name="recipient_city" id="recipientCityInput" required><br><br>

    <label for="recipient_postal_code">Recipient's postal code</label>
    <br>
    <input type="text" name="recipient_postal_code" id="recipientPostalCodeInput" required><br><br>

    <label for="bank_routing_number">Bank routing number/Sort code</label>
    <br>
    <input type="text" name="bank_routing_number" id="bankRoutingNumberInput" required><br><br>

    <label for="swift_code">Swift code</label>
    <br>
    <input type="text" name="swift_code" id="swiftCodeInput" required><br><br>

    <label for="narration">Narration (optional)</label>
    <br>
    <input type="text" name="narration" id="narrationInput"><br><br>

    <button type="submit">Send</button>
  </form>
</div>
<div id="poundForm" style="display: none;">
  <form action="/submit_pound_transfer" method="POST">
    <label for="amount">How much do you want to send?</label>
    <br>
    <input type="number" step="0.01" min="0.01" name="amount" id="amountInputPound" placeholder="Enter amount" required><br><br>

    <label for="bank_name">Bank name</label>
    <br>
    <input type="text" name="bank_name" id="bankNameInputPound" required><br><br>

    <label for="account_number">IBAN/Account number</label>
    <br>
    <input type="text" name="account_number" id="accountNumberInputPound" required><br><br>

    <label for="account_name">Account name</label>
    <br>
    <input type="text" name="account_name" id="accountNameInputPound" placeholder="Firstname Lastname" required><br><br>

    <label for="recipient_phone">Recipient's phone number (with country code)</label>
    <br>
    <input type="tel" name="recipient_phone" id="recipientPhoneInputPound" required pattern="(\+\d{1,3})\d{10,}"><br><br>

    <label for="recipient_email">Recipient's email</label>
    <br>
    <input type="email" name="recipient_email" id="recipientEmailInputPound" required><br><br>

    <label for="recipient_street_number">Recipient's street number</label>
    <br>
    <input type="text" name="recipient_street_number" id="recipientStreetNumberInput" required><br><br>

    <label for="recipient_street_name">Recipient's street name</label>
    <br>
    <input type="text" name="recipient_street_name" id="recipientStreetNameInput" required><br><br>

    <label for="recipient_city">Recipient's city</label>
    <br>
    <input type="text" name="recipient_city" id="recipientCityInput" required><br><br>

    <label for="recipient_postal_code">Recipient's postal code</label>
    <br>
    <input type="text" name="recipient_postal_code" id="recipientPostalCodeInput" required><br><br>

    <label for="bank_routing_number">Bank routing number/Sort code</label>
    <br>
    <input type="text" name="bank_routing_number" id="bankRoutingNumberInput" required><br><br>

    <label for="swift_code">Swift code</label>
    <br>
    <input type="text" name="swift_code" id="swiftCodeInput" required><br><br>

    <label for="narration">Narration (optional)</label>
    <br>
    <input type="text" name="narration" id="narrationInput"><br><br>

    <button type="submit">Transfer</button>
  </form>
</div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Get the elements from the DOM
const currencyForm = document.getElementById("currencyForm");
const currencySelect = document.getElementById("currencySelect");
const balanceContainer = document.getElementById("balanceContainer");

// Add event listener to the currency selection dropdown
currencySelect.addEventListener("change", function () {
  const selectedCurrency = currencySelect.value;

  if (selectedCurrency !== 'none') {
    // Use fetch instead of $.ajax for simplicity
    fetch(`./get_balance.php?currency=${selectedCurrency}`)
      .then(response => response.json())
      .then(data => {
        if (data.hasOwnProperty('balance')) {
          // Format balance with commas
          const formattedBalance = parseFloat(data.balance).toLocaleString(undefined, { minimumFractionDigits: 2 });
          // Update the content of the balanceContainer div without the "Balance:" text
          balanceContainer.textContent = formattedBalance + ' ' + selectedCurrency;
        } else if (data.hasOwnProperty('error')) {
          balanceContainer.textContent = 'Error: ' + data.error;
        }
      })
      .catch(error => {
        balanceContainer.textContent = 'Error fetching balance';
        console.log('Error: ' + error);
      });
  } else {
    balanceContainer.textContent = '';
  }
});
const accountNumberInput = document.getElementById("accountNumber");
const accountNameInput = document.getElementById("accountName");
const loadingSpinner = document.getElementById("loadingSpinner");
const bankSelect = document.getElementById("bank");

function showLoadingSpinner() {
  loadingSpinner.style.display = "inline-block";
}

function hideLoadingSpinner() {
  loadingSpinner.style.display = "none";
}

function verifyAccount(accountNumber, bankCode) {
  const formData = new FormData();
  formData.append("accountNumber", accountNumber);
  formData.append("bank", bankCode);

  fetch("verify_account.php", {
    method: "POST",
    body: formData,
  })
    .then(response => response.json())
    .then(data => {
      hideLoadingSpinner();
      if (data.accountName) {
        accountNameInput.value = data.accountName;
      } else {
        accountNameInput.value = "Account verification failed";
      }
    })
    .catch(error => {
      hideLoadingSpinner();
      console.error("Error verifying account:", error);
      accountNameInput.value = "Error verifying account";
    });
}

bankSelect.addEventListener("change", function () {
  const accountNumber = accountNumberInput.value.trim();
  const bankCode = bankSelect.value;

  if (accountNumber.length === 10 && /^\d+$/.test(accountNumber) && bankCode) {
    showLoadingSpinner();
    verifyAccount(accountNumber, bankCode);
  } else {
    hideLoadingSpinner();
    accountNameInput.value = ""; // Clear the account name field if the account number or bank is not selected
  }
});

accountNumberInput.addEventListener("input", function () {
  const accountNumber = accountNumberInput.value.trim();
  const bankCode = bankSelect.value;

  if (accountNumber.length === 10 && /^\d+$/.test(accountNumber) && bankCode) {
    showLoadingSpinner();
    verifyAccount(accountNumber, bankCode);
  } else {
    hideLoadingSpinner();
    accountNameInput.value = ""; // Clear the account name field if the account number or bank is not selected
  }
});

function populateBanks(data) {
  // Populate the bank options in the select element
  data.bankOptions.forEach(bank => {
    const option = document.createElement("option");
    option.value = bank.code;
    option.text = bank.name;
    bankSelect.appendChild(option);
  });
}

// Fetch available banks from Paystack API
fetch("get_banks.php")
  .then(response => response.json())
  .then(data => {
    populateBanks(data);
  })
  .catch(error => {
    console.error("Error fetching banks:", error);
  });
  </script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const currencySelect = document.getElementById("currencySelect");
    const nairaForm = document.getElementById("nairaForm");
    const ghanaForm = document.getElementById("ghanaForm");
    const usdForm = document.getElementById("usdForm");
    const poundForm = document.getElementById("poundForm");

    currencySelect.addEventListener("change", function () {
      const selectedCurrency = currencySelect.value;

      if (selectedCurrency === "Naira") {
        nairaForm.style.display = "block";
        ghanaForm.style.display = "none";
        usdForm.style.display = "none";
        poundForm.style.display = "none";
      } else if (selectedCurrency === "Cedi") {
        nairaForm.style.display = "none";
        ghanaForm.style.display = "block";
        usdForm.style.display = "none";
        poundForm.style.display = "none";
      } else if (selectedCurrency === "Dollar") {
        nairaForm.style.display = "none";
        ghanaForm.style.display = "none";
        usdForm.style.display = "block";
        poundForm.style.display = "none";
      } else if (selectedCurrency === "Pounds") {
        nairaForm.style.display = "none";
        ghanaForm.style.display = "none";
        usdForm.style.display = "none";
        poundForm.style.display = "block";
      } else {
        nairaForm.style.display = "none";
        ghanaForm.style.display = "none";
        usdForm.style.display = "none";
        poundForm.style.display = "none";
      }
    });
  });
</script>



</body>
</html>