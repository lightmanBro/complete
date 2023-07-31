<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

include '../../config.php';

$msg = "";

if (isset($_POST['submit'])) {
    $email = $_SESSION['SESSION_EMAIL'];
    $currentPassword = mysqli_real_escape_string($conn, md5($_POST['current-password']));
    $newPassword = mysqli_real_escape_string($conn, md5($_POST['new-password']));
    $confirmPassword = mysqli_real_escape_string($conn, md5($_POST['confirm-password']));

    // Retrieve the user's current password from the database
    $query = "SELECT password FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $dbPassword = $row['password'];

        // Verify the current password
        if ($currentPassword === $dbPassword) {
            // Check if the new password and confirm password match
            if ($newPassword === $confirmPassword) {
                // Update the password in the database
                $updateQuery = "UPDATE users SET password='$newPassword' WHERE email='$email'";
                $updateResult = mysqli_query($conn, $updateQuery);

                if ($updateResult) {
                    $msg = "<div class='alert alert-success'>Password changed successfully.</div>";
                } else {
                    $msg = "<div class='alert alert-danger'>Failed to change password. Please try again.</div>";
                }
            } else {
                $msg = "<div class='alert alert-danger'>New password and confirm password do not match.</div>";
            }
        } else {
            $msg = "<div class='alert alert-danger'>Current password is incorrect.</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>User not found.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="fontsawesome/css/all.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="color.css">
    <link rel="stylesheet" href="./style/style.css">

    <link rel="stylesheet" href="./style/sendmoney.css">
   <title>Tamopei | Change Password</title>
</head>

<body>
    <div class="container">
        <aside class="">
            <div class="top">
                <div class="logo">

                    <h2><a href="">
                            <img src="./img/colorLogo.png" alt="">
                        </a></h2>
                </div>
            </div>

            <div class="sidebar">
                <a href="../../Dashboard" class="active">
                    <span class="b material-icons-sharp">
                        grid_view
                    </span>
                    <h3 class="b">Dashboard</h3>
                </a>
                <a href=""><span class="b material-icons-sharp">
                        account_balance
                    </span>
                    <h3 class="b">Banks</h3>
                    </span>
                </a>
                <a href=""><span class="b material-icons-sharp">
                        add
                    </span>
                    <h3 class="b">Orders</h3>
                    </span>
                </a>
                <a href=""><span class="b material-icons-sharp">
                        notifications_none
                    </span>
                    <h3 class="b">Notifications</h3>
                    <div class="circle">
                        <h3 class="white">23</h3>
                    </div>
                    </span>
                </a>
                <a href=""><span class="b material-icons-sharp">
                        history
                    </span>
                    <h3 class="b">History</h3>
                    </span>
                </a>
                <a href=""><span class="b material-icons-sharp">
                        tune
                    </span>
                    <h3 class="b">Settings</h3>
                    </span>
                </a>
                <a href="../../logout.php"><span class="b material-icons-sharp">
                        power_settings_new
                    </span>
                    <h3 class="b">Log out</h3>
                    </span>
                </a>
            </div>
        </aside>
        <main>
        <div class="table-section-o">
    <table>
      <thead>
        <tr>
          <th onclick="toggleTable(0)"><i class="fa-regular fa-user"></i><span>Change Password</span></th>
        </tr>
      </thead>
      <td show colspan="5" class="table-content">
              <div class="table-info">
   
   <div class="form-container">
      <form action="" method="post">
         <?php echo $msg; ?>
         <input type="password" name="current-password" placeholder="Current Password" class="box" required>
         <input type="password" name="new-password" placeholder="New Password" class="box" required>
         <input type="password" name="confirm-password" placeholder="Confirm Password" class="box" required>
         <button name="submit" class="btn" type="submit">Change Password</button>
      </form>
   </div>
<!-- Start of LiveChat (www.livechat.com) code -->
<script>
    window.__lc = window.__lc || {};
    window.__lc.license = 15349164;
    ;(function(n,t,c){function i(n){return e._h?e._h.apply(null,n):e._q.push(n)}var e={_q:[],_h:null,_v:"2.0",on:function(){i(["on",c.call(arguments)])},once:function(){i(["once",c.call(arguments)])},off:function(){i(["off",c.call(arguments)])},get:function(){if(!e._h)throw new Error("[LiveChatWidget] You can't use getters before load.");return i(["get",c.call(arguments)])},call:function(){i(["call",c.call(arguments)])},init:function(){var n=t.createElement("script");n.async=!0,n.type="text/javascript",n.src="https://cdn.livechatinc.com/tracking.js",t.head.appendChild(n)}};!n.__lc.asyncInit&&e.init(),n.LiveChatWidget=n.LiveChatWidget||e}(window,document,[].slice))
</script>
<noscript><a href="https://www.livechat.com/chat-with/15349164/" rel="nofollow">Chat with us</a>, powered by <a href="https://www.livechat.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a></noscript>
<!-- End of LiveChat code -->

          <script src="./forms.js"></script>
    <script src="./userSettings.js"></script>
    <script src="./script.js"></script>
        
</body>

</html>
