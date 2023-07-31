<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}
include '../../server.php';

$query = $pdo->prepare("SELECT * FROM user_credentials WHERE email=:email");
$query->bindValue(':email', $_SESSION['email']);
$query->execute();

if ($query->rowCount() > 0) {
    $fetch = $query->fetch(PDO::FETCH_ASSOC);
    $referral_link = $fetch['referral_link']; // Fetch the referral link from the 'referral_link' column in the user_credentials table
} else {
    $referral_link = ''; // Set the referral link to an empty string if not found
}

$kycQuery = $pdo->prepare("SELECT status FROM kyc_verification WHERE user_id=:user_id");
$kycQuery->bindValue(':user_id', $fetch['user_id']);
$kycQuery->execute();

if ($kycQuery->rowCount() > 0) {
    $kycStatus = $kycQuery->fetch(PDO::FETCH_ASSOC)['status'];
} else {
    $kycStatus = '';
}

$submitted = isset($_SESSION['kyc_submitted']) && $_SESSION['kyc_submitted'] === true;
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

    <title>Tamopie Home</title>
    <style>
 .kyc-approved {
  margin-bottom: 1rem;
  font-family: 'Work Sans', sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 1rem;
  color: #555555;
}

.form-row {
  margin-bottom: 1rem;
}

.form-row label {
  display: block;
  font-size: 0.9rem;
  font-weight: 600;
  color: #333333;
  margin-bottom: 0.5rem;
}

.form-row .box {
  width: 100%;
  font-size: 0.9rem;
  padding: 0.5rem;
  color: #333333;
  background-color: #f2f2f2;
  border-radius: 2px;
  border: 1px solid #dddddd;
}

.form-row .image-container {
  margin-top: 0.5rem;
}

.form-row .image-container img {
  max-width: 100%;
  height: auto;
}


</style>
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
                </a>
                <a href=""><span class="b material-icons-sharp">
                        add
                    </span>
                    <h3 class="b">Orders</h3>
                </a>
                <a href=""><span class="b material-icons-sharp">
                        notifications_none
                    </span>
                    <h3 class="b">Notifications</h3>
                    <div class="circle">
                        <h3 class="white">23</h3>
                    </div>
                </a>
                <a href=""><span class="b material-icons-sharp">
                        history
                    </span>
                    <h3 class="b">History</h3>
                </a>
                <a href=""><span class="b material-icons-sharp">
                        tune
                    </span>
                    <h3 class="b">Settings</h3>
                </a>
                <a href="../../logout.php"><span class="b material-icons-sharp">
                        power_settings_new
                    </span>
                    <h3 class="b">Log out</h3>
                </a>
            </div>
        </aside>
        <main>
            <div class="table-section-o">
                <table>
                    <thead>
                        <tr>
                            <th onclick="toggleTable(0)"><i class="fa-regular fa-user"></i><span>Profile</span></th>
                            <th onclick="toggleTable(01)"><i class="fa-solid fa fa-bank"></i><span>Banks</span></th>
                            <th onclick="toggleTable(1)"><i class="fa-solid fa fa-file"></i><span>KYC Verification</span></th>
                            <th onclick="toggleTable(2)"><i class="fa-solid fa-exchange"></i><span>Referral</span></th>
                            <th onclick="toggleTable(3)"><i class="fa-solid fa-level-up"></i><span>Level</span></th>
                        </tr>
                    </thead>
                    <tr>
                        <td show colspan="5" class="table-content">
                            <div class="table-info">
                                <form action="" method="post" enctype="">
                                    <label for="name" class="label">Full Name:</label>
                                    <input type="text" name="name" class="box" value="<?php echo $fetch['first_name']; ?> <?php echo $fetch['middle_name']; ?> <?php echo $fetch['last_name']; ?>" readonly><br><br>
                                    <label for="username" class="label">UserName:</label>
                                    <input type="text" id="username" name="username" class="box" value="<?php echo $fetch['username']; ?>" readonly><br><br>
                                    <label for="username" class="label">Email:</label>
                                    <input type="email" id="email" name="email" class="box" value="<?php echo $fetch['email']; ?>" readonly><br><br>
                                    <label for="username" class="label">Phone Number:</label>
                                    <input type="tel" id="phone" name="phone" class="box" value="<?php echo $fetch['phone']; ?>" readonly><br><br>
                                    <label for="username" class="label">Country:</label>
                                    <input type="text" id="countryId" name="country" class="countries form-control; box" value="<?php echo $fetch['country']; ?>" readonly><br><br>
                                    <!--<label for="username" class="label">Upload Picture:</label>
                                <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box"><br></br>--->
                                    <div class="inputBox">
                                        <a href="changepassword.php" class="btn">Change Password</a>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td show colspan="5" class="table-content">
                            <div class="table-info">
                                <form action="bank_details_processing.php" method="post">
                                    <h3>Bank Details</h3>
                                    <input type="text" name="bank_name" placeholder="Bank Name" class="box" required>
                                    <input type="text" name="account_number" placeholder="Account Number" class="box" required>
                                    <input type="text" name="account_name" placeholder="Account Name" class="box" required>
                                    <button name="submit" class="btn" type="submit">Save Bank Details</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr>
    <td show colspan="5" class="table-content">
        <div class="table-info">
        <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
            <?php if ($kycStatus === ''): ?>
                <form method="post" action="kyc_verification_process.php" enctype="multipart/form-data">
                    <div class="form-row">
                        <label for="document_type">Document Type:</label>
                        <select name="document_type" id="document_type" class="box">
                            <option value="passport">Passport</option>
                            <option value="national_id">National ID</option>
                            <option value="driving_license">Driving License</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label for="document_number">Document Number:</label>
                        <input type="text" name="document_number" id="document_number" class="box">
                    </div>
                    <div class="form-row">
                        <label for="front_id_image">Front ID Image:</label>
                        <input type="file" name="front_id_image" id="front_id_image" class="box">
                    </div>
                    <div class="form-row">
                        <label for="back_id_image">Back ID Image:</label>
                        <input type="file" name="back_id_image" id="back_id_image" class="box">
                    </div>
                    <div class="form-row">
                        <input type="submit" name="submit" value="Submit" class="btn">
                    </div>
                </form>
            <?php else: ?>
                <?php
                // Fetch KYC verification status from kyc_verification table
                $kycQuery = $pdo->prepare("SELECT * FROM kyc_verification WHERE user_id = :user_id");
                $kycQuery->bindValue(':user_id', $fetch['user_id']);
                $kycQuery->execute();
                $kycRow = $kycQuery->fetch(PDO::FETCH_ASSOC);
                $status = strtolower($kycRow['status']); // Add this line to define the $status variable
                ?>

                <?php if ($status === 'pending'): ?>
                    <!-- KYC verification submitted message -->
                    <p class="kyc-verification-info">KYC verification submitted successfully.</p>
<p class="kyc-verification-info">Status: <?php echo $status; ?></p>
<p class="kyc-verification-info">Kindly wait for the admin to verify your KYC document. Thank you!</p>

                <?php elseif ($status === 'rejected'): ?>
                    <!-- KYC verification rejected message -->
                    <p>Your KYC document has been rejected by the admin.</p>
                    <p>Please make the necessary changes and resubmit the form.</p>
                    <form method="post" action="kyc_verification_process.php" enctype="multipart/form-data">
                        <div class="form-row">
                            <label for="document_type">Document Type:</label>
                            <select name="document_type" id="document_type" class="box">
                                <option value="passport">Passport</option>
                                <option value="national_id">National ID</option>
                                <option value="driving_license">Driving License</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <label for="document_number">Document Number:</label>
                            <input type="text" name="document_number" id="document_number" class="box">
                        </div>
                        <div class="form-row">
                            <label for="front_id_image">Front ID Image:</label>
                            <input type="file" name="front_id_image" id="front_id_image" class="box">
                        </div>
                        <div class="form-row">
                            <label for="back_id_image">Back ID Image:</label>
                            <input type="file" name="back_id_image" id="back_id_image" class="box">
                        </div>
                        <div class="form-row">
                            <input type="submit" name="submit" value="Resubmit" class="btn">
                        </div>
                    </form>
                <?php elseif ($status === 'approved'): ?>
                    <!-- KYC verification approved message -->
                    <p class="kyc-info">Your KYC document has been approved by the admin.</p>
                    <div class="form-row">
  <label class="label" for="document_type">Document Type:</label>
  <input type="text" name="document_type" class="box" value="<?php echo isset($kycRow['document_type']) ? $kycRow['document_type'] : ''; ?>" readonly><br><br>
</div>
<div class="form-row">
  <label class="label" for="document_number">Document Number:</label>
  <input type="text" name="document_number" class="box" value="<?php echo isset($kycRow['document_number']) ? $kycRow['document_number'] : ''; ?>" readonly><br><br>
</div>
<div class="kyc-info-row">
  <label class="kyc-info-label" for="front_id_image">Front ID Image:</label>
  <div class="kyc-info-image-container">
    <img class="kyc-info-image" src="<?php echo isset($kycRow['front_id_image']) ? $kycRow['front_id_image'] : ''; ?>" alt="Front ID Image"><br><br>
  </div>
</div>
<div class="kyc-info-row">
  <label class="kyc-info-label" for="back_id_image">Back ID Image:</label>
  <div class="kyc-info-image-container">
    <img class="kyc-info-image" src="<?php echo isset($kycRow['back_id_image']) ? $kycRow['back_id_image'] : ''; ?>" alt="Back ID Image"><br><br>
  </div>
</div>
                </div>

                <?php endif; ?>
            <?php endif; ?>
        </div>
    </td>
</tr>



            </tr>
                    <tr>
  <td show colspan="5" class="table-content">
    <div class="table-info">
      <h2>Referral Link</h2>
      <div class="referral-box">
        <span class="referral-link"><?php echo $referral_link; ?></span>
        <button class="copy-button" onclick="copyReferralLink()">Copy</button>
        <button class="share-button" onclick="shareReferralLink()">Share</button>
      </div>
    </div>
  </td>
</tr>
                    <tr>
                        <td show colspan="5" class="table-content">
                        <div class="table-info">
  <?php
  // Fetch the user's level from the users table
  $levelQuery = "SELECT level FROM user_credentials WHERE email = '{$_SESSION['email']}'";
  $levelResult = $pdo->query($levelQuery);
  $level = $levelResult->fetchColumn();

  if ($level !== false) {
    echo "<h3 class='level-available'>Your current level is ($level)</h3>";
  } else {
    echo "<h3 class='level-not-available'>Your level is not available</h3>";
  }
  ?>
  <br><br>
</div>

                        </td>
                    </tr>
                </table>
            </div>
        </main>
    </div>

    <!-- Start of LiveChat (www.livechat.com) code -->
    <script>
        window.__lc = window.__lc || {};
        window.__lc.license = 15349164;
        ;(function(n,t,c){function i(n){return e._h?e._h.apply(null,n):e._q.push(n)}var e={_q:[],_h:null,_v:"2.0",on:function(){i(["on",c.call(arguments)])},once:function(){i(["once",c.call(arguments)])},off:function(){i(["off",c.call(arguments)])},get:function(){if(!e._h)throw new Error("[LiveChatWidget] You can't use getters before load.");return i(["get",c.call(arguments)])},call:function(){i(["call",c.call(arguments)])},init:function(){var n=t.createElement("script");n.async=!0,n.type="text/javascript",n.src="https://cdn.livechatinc.com/tracking.js",t.head.appendChild(n)}};!n.__lc.asyncInit&&e.init(),n.LiveChatWidget=n.LiveChatWidget||e}(window,document,[].slice))
    </script>
    <noscript><a href="https://www.livechat.com/chat-with/15349164/" rel="nofollow">Chat with us</a>, powered by <a href="https://www.livechat.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a></noscript>
    <!-- End of LiveChat code -->
    <script>
 function copyReferralLink() {
  const referralLink = document.querySelector('.referral-link').textContent;

  // Create a temporary textarea element to copy the referral link
  const textarea = document.createElement('textarea');
  textarea.value = referralLink;
  textarea.setAttribute('readonly', '');
  textarea.style.position = 'absolute';
  textarea.style.left = '-9999px';
  document.body.appendChild(textarea);

  // Copy the referral link to the clipboard
  textarea.select();
  document.execCommand('copy');
  document.body.removeChild(textarea);

  // Show a success message or perform any other desired actions
  alert('Referral link copied to clipboard!');
}

function shareReferralLink() {
  const referralLink = document.querySelector('.referral-link').textContent;

  if (navigator.share) {
    navigator
      .share({
        title: 'Share Referral Link',
        text: 'Check out this referral link!',
        url: referralLink
      })
      .then(() => {
        console.log('Referral link shared successfully.');
      })
      .catch((error) => {
        console.error('Error sharing referral link:', error);
      });
  } else {
    // Fallback behavior if Web Share API is not supported
    copyReferralLink();
  }
}

</script>
    <script src="./forms.js"></script>
    <script src="./userSettings.js"></script>
    <script src="./script.js"></script>

</body>

</html>
