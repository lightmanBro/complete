<?php
// Include the database connection file
require_once 'server.php';
// session_start
// Define an empty message variable
$msg = "";

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get the form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Retrieve the user data from the database
    $sql = "SELECT * FROM user_credentials WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashedPassword = $row['password'];
        $isVerified = $row['verification_status'];
        $country = $row['country'];
        $accountNumber = $row['account_number'];

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            if ($isVerified) {
                // Password is correct and account is verified, check account number if user is from Nigeria
                if ($country == 'Nigeria' && empty($accountNumber)) {
                    // Set the email in the session
                    session_start();
                    $_SESSION['email'] = $email;
                    // Redirect to complete registration page
                    header("Location: complete_registration.php");
                    exit();
                } else {
                    // Set the email in the session
                    $sql = "SELECT user_id FROM user_credentials WHERE email = :email";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();
                    if ($stmt->rowCount() == 1) {
                        session_start();
                        $_SESSION['user_id'] = $row['user_id'];
                        $_SESSION['email'] = $row['email'];
                        header("Location: dashboard/index.php");
                        //print_r($_SESSION['email']." ".$_SESSION['user_id']);
                    }
                    exit();
                }
            } else {
                // Account is not verified
                $verificationData = array(
                    'email' => $email,
                    'password' => $password
                );
                // Store the verification data in a session variable
                session_start();
                $_SESSION['verificationData'] = $verificationData;

                // Redirect to the account verification page after 5 seconds
                echo '<script>
                    setTimeout(function() {
                        window.location.href = "account_verification.php";
                    }, 5000);
                </script>';
                exit();
            }
        } else {
            $msg = "Incorrect email or password.";
        }
    } else {
        $msg = "Incorrect email or password.";
    }
}
?>



<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/btn.css">
    <style>
        /* Mobile-first styles */
        .form-container {
            max-width: 100%;
            padding: 10px;
        }

        .form-container h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .box {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
        }

        /* Footer styles */
        footer {
            text-align: center;
            margin-top: 790px;
            padding: 10px;
            background-color: #f2f2f2;
            color: #5ea536;
            font-weight: bold;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>

<body>
    <div id="textbox">
        <p class="alignleft"><a href="">
                <img src="./img/colorLogo.png" alt="">
            </a></p>
        <a class="alignright-btn" href="register.php"> Create Account </a>
        <div style="clear: both;"></div>
    </div>
    <div class="form-container">
        <form action="" method="post">
            <h3>Login</h3>
            <div id="message"><?php echo $msg; ?></div>
            <input type="email" name="email" placeholder="Email" class="box" required>
            <input type="password" name="password" placeholder="Password" class="box" autocomplete="current-password" required>
            <button name="submit" class="btn" type="submit">Login</button>
        </form>
    </div>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Your Website. All rights reserved.</p>
    </footer>
    <script src="js/jquery.min.js"></script>
    <script>
        $(document).ready(function(c) {
            $('.alert-close').on('click', function(c) {
                $('.main-mockup').fadeOut('slow', function(c) {
                    $('.main-mockup').remove();
                });
            });
        });
    </script>
</body>

</html>