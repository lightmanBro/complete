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

// Check if the email parameter is set
if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Check if the verification code is submitted
    if (isset($_POST['verify'])) {
        $verificationCode = $_POST['verification-code'];

        // Check if the verification code matches the one in the database
        $sql = "SELECT * FROM user_credentials WHERE email = ? AND verification_code = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email, $verificationCode]);

        if ($stmt->rowCount() > 0) {
            // Update the user's verification status
            $sql = "UPDATE user_credentials SET verification_status = 1 WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);

            // Send verification success email
            sendVerificationSuccessEmail($email);

            $msg = "Email verification successful! You can now login.";
        } else {
            // Check if the verification code matches without hyphens
            $verificationCodeWithHyphens = substr_replace($verificationCode, '-', 4, 0);
            $verificationCodeWithHyphens = substr_replace($verificationCodeWithHyphens, '-', 9, 0);
            $verificationCodeWithHyphens = substr_replace($verificationCodeWithHyphens, '-', 14, 0);

            $sql = "SELECT * FROM user_credentials WHERE email = ? AND verification_code = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email, $verificationCodeWithHyphens]);

            if ($stmt->rowCount() > 0) {
                // Update the user's verification status
                $sql = "UPDATE user_credentials SET verification_status = 1 WHERE email = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$email]);

                // Send verification success email
                sendVerificationSuccessEmail($email);

                $msg = "Email verification successful! You can now login.";
            } else {
                $msg = "Invalid verification code.";
            }
        }
    }
} else {
    // Redirect to the registration page if the email parameter is not set
    header("Location: registration.php");
    exit;
}

// Function to send the verification success email
function sendVerificationSuccessEmail($email)
{
    // Instantiation and passing the `true` enables exceptions
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
        $mail->Subject = 'Email Verification Successful';
        $mail->Body = 'Your account has been verified successfully. You can now log in to your account.';

        // Send the email
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tamopei | Email Verification</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Email Verification</h3>
            <?php echo $msg; ?>
            <input type="text" name="verification-code" placeholder="Verification Code" class="box" required>
            <button name="verify" class="btn" type="submit">Verify</button>
        </form>
    </div>

    <?php
    if (!empty($msg)) {
        if ($msg === "Email verification successful! You can now login.") {
            echo '<script>
                setTimeout(function() {
                    window.location.href = "index.php";
                }, 5000);
            </script>';
        }
    }
    ?>
</body>
</html>
