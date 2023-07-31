<?php
// Start the session
session_start();

// Check if the verification data is present in the session
if (isset($_SESSION['verificationData'])) {
    // Get the email, password, and verification code from the session
    $verificationData = $_SESSION['verificationData'];
    $email = $verificationData['email'];
    $password = $verificationData['password'];

    // Split the verification code into segments
    $verificationCode = str_replace('-', '', $verificationData['verificationCode']);
    $segments = str_split($verificationCode, 4);
} else {
    // Redirect to the login page if the verification data is not present
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Account Verification</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/btn.css">
    <style>
        .verification-code {
            display: flex;
        }

        .verification-code input {
            flex: 1;
            max-width: 60px;
            text-align: center;
        }

        .verification-code span {
            padding: 0 5px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form action="verify.php" method="post">
            <h3>Account Verification</h3>
            <div id="message">Please enter the verification code sent to your email.</div>
            <input type="hidden" name="email" value="<?php echo $email; ?>">
            <input type="hidden" name="password" value="<?php echo $password; ?>">

            <div class="verification-code">
                <?php foreach ($segments as $segment): ?>
                <input type="text" name="verification_code[]" maxlength="4" value="<?php echo $segment; ?>" required>
                <?php endforeach; ?>
            </div>
            
            <button name="submit" class="btn" type="submit">Verify</button>
        </form>
    </div>
    <script src="js/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            // Handle paste event on the first input field
            $('input[name="verification_code[]"]:eq(0)').on('paste', function(e) {
                var pastedData = e.originalEvent.clipboardData.getData('text');
                var segments = pastedData.split('-').join('').split('');
                
                // Fill in the input fields with pasted segments
                $('input[name="verification_code[]"]').each(function(index, element) {
                    if (segments[index]) {
                        $(element).val(segments[index]);
                    }
                });
                
                // Prevent default paste behavior
                e.preventDefault();
            });
        });
    </script>
</body>
</html>
