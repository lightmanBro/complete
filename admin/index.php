<?php
session_start();
if (isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: dashboard.php");
    die();
}

include '../server.php';

$msg = "";

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM admin WHERE email=:email AND password=:password";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $row = $stmt->fetch();

        $_SESSION['SESSION_EMAIL'] = $email;
        header("Location: dashboard.php");
        exit();
    } else {
        $msg = "<div class='alert alert-danger'>Email or password do not match.</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Login</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/btn.css">
</head>
<body>
<div id="textbox">
  <p class="alignleft"><a href="">
                        <img src="./img/colorLogo.png" alt="">
                    </a></p>
</div>  
<div class="form-container">
   <form action="" method="post" enctype="multipart/form-data">
      <h3>Admin Panel Login</h3>
      <?php echo $msg; ?>
      <input type="email" name="email" placeholder="Email" class="box" required>
      <input type="password" name="password" placeholder="Password" class="box" required>
      <input type="submit" name="submit" value="Login Now" class="btn">
      <p><a href="forgot_password.php">Forgot Password?</a></p>
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
