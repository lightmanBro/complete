<!DOCTYPE html>
<html>
<head>
  <title>Account Verification</title>
  <style>
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
  </style>
</head>
<body>
  <h1>Account Verification</h1>

  <form id="verificationForm">
    <label for="bank">Bank:</label>
    <select id="bank" name="bank">
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
    <input type="text" id="accountName" name="accountName" readonly>
    <br><br>
  </form>

  <script>
    const accountNumberInput = document.getElementById("accountNumber");
    const accountNameInput = document.getElementById("accountName");
    const loadingSpinner = document.getElementById("loadingSpinner");

    document.getElementById("verificationForm").addEventListener("submit", function (event) {
      event.preventDefault();
    });

    accountNumberInput.addEventListener("input", function () {
      const accountNumber = accountNumberInput.value.trim();
      if (accountNumber.length === 10 && /^\d+$/.test(accountNumber)) {
        showLoadingSpinner();
        verifyAccount();
      } else {
        hideLoadingSpinner();
        accountNameInput.value = ""; // Clear the account name field if the account number is not valid
      }
    });

    function showLoadingSpinner() {
      loadingSpinner.style.display = "inline-block";
    }

    function hideLoadingSpinner() {
      loadingSpinner.style.display = "none";
    }

    function verifyAccount() {
      const formData = new FormData(document.getElementById("verificationForm"));

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

    // Fetch available banks from Paystack API
    fetch("get_banks.php")
      .then(response => response.json())
      .then(data => {
        const bankSelect = document.getElementById("bank");

        // Populate the bank options in the select element
        data.bankOptions.forEach(bank => {
          const option = document.createElement("option");
          option.value = bank.code;
          option.text = bank.name;
          bankSelect.appendChild(option);
        });
      })
      .catch(error => {
        console.error("Error fetching banks:", error);
      });
  </script>
</body>
</html>
