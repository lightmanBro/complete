<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Include the database connection file
require_once 'server.php';

// Define an empty message variable
$msg = "";

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get the form data
    $firstName = $_POST['first_name'];
    $middleName = $_POST['middle_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Perform validation on the form data
    // (Add your own validation rules as per your requirements)
    if (empty($firstName) || empty($lastName) || empty($email) || empty($username) || empty($phone) || empty($address) || empty($country) || empty($state) || empty($city) || empty($password) || empty($confirmPassword)) {
        $msg = "Please fill in all the fields.";
    } elseif ($password != $confirmPassword) {
        $msg = "Passwords do not match.";
    } else {
        // Check if the username already exists
        $sql = "SELECT * FROM user_credentials WHERE username = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $msg = "Username already exists.";
        } else {
            // Check if the email already exists
            $sql = "SELECT * FROM user_credentials WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $msg = "Email already exists.";
            } else {
                // Generate a stronger password hash using bcrypt
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Check if the referral ID exists in the URL
                if (isset($_GET['ref'])) {
                    $referredBy = $_GET['ref'];
                } else {
                    $referredBy = null; // Set a default value if the referral ID is not present
                }

                // Generate the referral ID using the username and random three-digit numbers
                $referralID = generateReferralID($username);

                // Generate a verification code
                $verificationCode = generateVerificationCode();

                // Generate the referral link using the referral ID
                $referralLink = generateReferralLink($referralID, $username);
                // Generate user ID
                $userId = generateuserId();
                
                // Generate a default value for the account number if it is null
                $accountNumber = null;
                if ($accountNumber === null) {
                    $accountNumber = '';
                }

                // Insert the user data into the database
                try {
                    $stmt = $pdo->prepare("INSERT INTO user_credentials (first_name, middle_name, last_name, email, username, phone, address, country, state, city, account_number, password, referral_id, referral_link, verification_code, level, kyc_status, verification_status, referred_by, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                    $stmt->execute([$firstName, $middleName, $lastName, $email, $username, $phone, $address, $country, $state, $city, $accountNumber, $hashedPassword, $referralID, $referralLink, $verificationCode, 'Tier 1', 'unverified', 0, $referredBy, $userId]);
                    // Send verification email
                    sendVerificationEmail($email, $verificationCode, $username);

                    // Set the success message
                    $msg = "Registration successful! Please check your email to verify your account.";
                } catch (PDOException $e) {
                    $msg = "Error: " . $e->getMessage();
                }
            }
        }
    }
}

// Function to generate a referral ID
function generateReferralID($username)
{
    // Generate a random three-digit number
    $randomNumber = rand(100, 999);

    // Combine the username and the random number to create the referral ID
    $referralID = $username . $randomNumber;

    return $referralID;
}

// Function to generate a verification code
function generateVerificationCode()
{
    // Generate four random four-digit numbers
    $codePart1 = mt_rand(1000, 9999);
    $codePart2 = mt_rand(1000, 9999);
    $codePart3 = mt_rand(1000, 9999);
    $codePart4 = mt_rand(1000, 9999);

    // Generate the verification code in the format "XXXX-XXXX-XXXX-XXXX"
    $verificationCode = $codePart1 . '-' . $codePart2 . '-' . $codePart3 . '-' . $codePart4;

    return $verificationCode;
}
// Function to generate user ID
function generateuserId()
{
    // Generate a random ten-digit number
    $randomNumber = mt_rand(1000000000, 9999999999);

    // Generate the user ID
    $userId = $randomNumber;

    return $userId;
}

// Function to generate the referral link
function generateReferralLink($referralID, $username)
{
    // Customize the base URL here
    $baseURL = "http://localhost/register/register.php";

    // Generate the referral link using the referral ID and username
    $referralLink = $baseURL . "?ref=" . $referralID;

    return $referralLink;
}


// Function to send verification email
function sendVerificationEmail($email, $verificationCode, $username)
{
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Configure SMTP settings
        $mail->isSMTP();
        $mail->Host = 'mail.babaneeh.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'info@babaneeh.com';
        $mail->Password = 'Oluwasemiloremi';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Set the email content
        $mail->setFrom('admin@tamopei.com', 'Tamopei');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';

        // Customize the email body
        $message = "Dear $username,<br><br>";
$message .= "Thank you for registering on Tamopei. Here is your verification code: $verificationCode<br><br>";
$message .= "Kindly verify your account to continue your registration.<br><br>";
$message .= '<a href="http://localhost/register/verification.php?email=' . urlencode($email) . '">Click here to enter your verification code</a>';
        $mail->Body = $message;

        // Send the email
        $mail->send();
    } catch (Exception $e) {
        // Handle the exception if sending the email fails
        $msg = "Error sending verification email: " . $mail->ErrorInfo;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tamopei | Register</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/mobile.css">
    <link rel="stylesheet" href="css/btn.css">
    <link rel="stylesheet" href="css/intlTelInput.css">
  
  <script src="js/countrystatecity.js?v2"></script>
  <style>

/* Media Queries */

/* Styles for screens smaller than 600px (mobile devices) */
@media (max-width: 600px) {
  .form-container {
    max-width: 100%;
    padding: 10px;
  }

  .form-container input[type="text"],
  .form-container input[type="email"],
  .form-container input[type="password"],
  .form-container select {
    padding: 8px;
  }
}
</style>
</head>
<body>
<div id="textbox">
  <p class="alignleft"><a href="">
                        <img src="./img/colorLogo.png" alt="">
                    </a></p>
  <p class="alignright-btn"> Already have an account? <span><a href="index.php"> Login now </a></span></p>
  <div style="clear: both;"></div>
</div>
    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Register Now</h3>
            <?php
            if (!empty($msg)) {
                echo '<div class="success-msg">' . $msg . '</div>';
            }
            ?>
            <input type="text" name="first_name" placeholder="First Name" class="box" required>
            <input type="text" name="middle_name" placeholder="Middle Name" class="box">
            <input type="text" name="last_name" placeholder="Last Name" class="box" required>
            <input type="email" name="email" placeholder="Email" class="box" required>
            <input type="text" name="username" placeholder="Username" class="box" required>
            <input type="tel" name="phone" id="phone" Placeholder="Phone Number" required maxlength="15">
            <input type="text" name="address" placeholder="Address" class="box" required>
            <select name="country" class="countries form-control; box" id="countryId" required>
    <option value="">Select Country</option>
</select>
<select name="state" class="states form-control; box" id="stateId" required>
    <option value="">Select State</option>
</select>
<select name="city" class="cities form-control; box" id="cityId" required>
    <option value="">Select City</option>
</select>
            <input type="password" id="password" name="password" placeholder="Password" class="box" required>
            <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-confirm"></span>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Password" class="box" required>
            <span toggle="#confirm-password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
            <input type="submit" name="submit" value="Register" class="btn">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
    <?php
if (!empty($msg)) {
    if ($msg === "Registration successful! Please check your email to verify your account.") {
        echo '<script>
            setTimeout(function() {
                window.location.href = "verification.php?email=' . urlencode($email) . '";
            }, 5000);
          </script>';
    }
}
?>
    <script src="js/jquery.min.js"></script>
    <script>
        $(document).ready(function (c) {
            $('.alert-close').on('click', function (c) {
                $('.main-mockup').fadeOut('slow', function (c) {
                    $('.main-mockup').remove();
                });
            });
        });
    </script>
    <script>
   $(".toggle-password").click(function() {

  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
</script>
<script>
   $(".toggle-confirm").click(function() {

  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
</script>

<script src="js/intlTelInput.js"></script>
<script src="js/countrystatecity.js"></script>
    <script>
    // Vanilla Javascript
    var input = document.querySelector("#phone");
    window.intlTelInput(input,({
      // options here
    }));

    $(document).ready(function() {
        $('.iti__flag-container').click(function() { 
          var countryCode = $('.iti__selected-flag').attr('title');
          var countryCode = countryCode.replace(/[^0-9]/g,'')
          $('#phone').val("");
          $('#phone').val("+"+countryCode+" "+ $('#phone').val());
       });
    });
  </script>

</body>

</html>