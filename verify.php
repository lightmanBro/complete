<?php
// Include the database connection file
require_once 'server.php';

// Check if the form is submitted
if(isset($_POST['submit'])){
    // Get the form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $verificationCode = $_POST['verification_code'];

    // Retrieve the user data from the database
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $storedVerificationCode = $row['verification_code'];

        // Verify the entered verification code
        if ($verificationCode == $storedVerificationCode) {
            // Verification code is correct
            // Update the verification status of the user to indicate that the account is verified
            $updateSql = "UPDATE users SET verification_status = 1 WHERE email = :email";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->bindParam(':email', $email);
            $updateStmt->execute();

            // Redirect to the login page with a success message
            $msg = "Account verification successful. You can now log in.";
            header("Location: login.php?msg=" . urlencode($msg));
            exit();
        } else {
            // Verification code is incorrect
            $msg = "Incorrect verification code. Please try again.";
        }
    } else {
        // User not found
        $msg = "User not found.";
    }
} else {
    // Redirect to the login page if the form is not submitted
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
</head>
<body>
    <div class="form-container">
        <form action="" method="post">
            <h3>Account Verification</h3>
            <div id="message"><?php echo $msg; ?></div>
            <input type="hidden" name="email" value="<?php echo $email; ?>">
            <input type="hidden" name="password" value="<?php echo $password; ?>">
            <input type="text" name="verification_code" placeholder="Verification Code" class="box" required>
            <button name="submit" class="btn" type="submit">Verify</button>
        </form>
    </div>
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
</body>
</html>
