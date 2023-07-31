<?php
session_start();
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

include '../server.php';

$sql = "SELECT kyc.*, CONCAT(user_credentials.first_name, ' ', user_credentials.middle_name) AS full_name, user_credentials.kyc_status 
        FROM kyc_verification AS kyc
        INNER JOIN user_credentials ON kyc.user_id = user_credentials.user_id";

$query = $pdo->query($sql);
$result = $query->fetchAll(PDO::FETCH_ASSOC);

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="./style/sendmoney.css">
    <style>
    main {
        display: flex;
        justify-content: start;
        align-items: flex-start;
        min-height: 100vh;
        background-color: #f2f2f2;
        padding: 20px;
        box-sizing: border-box;
    }

    section {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        flex: 1;
        padding: 20px;
        box-sizing: border-box;
    }

    h2 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    div {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 20px;
    }

    p {
        margin: 5px 0;
        font-size: 24px;
    }

    .btn {
        background-color: #4CAF50;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 10px;
    }

    .btn:hover {
        background-color: #45a049;
    }
    </style>

    <title>Tamopie Admin Dashboard</title>
</head>

<body>
    <div class="container">
        <aside>
            <div class="top">
                <div class="logo">
                    <h2><a href=""><img src="./img/colorLogo.png" alt=""></a></h2>
                </div>
            </div>

            <div class="sidebar">
                <a href="../../Dashboard" class="active">
                    <span class="b material-icons-sharp">
                        grid_view
                    </span>
                    <h3 class="b">Dashboard</h3>
                </a>
                <a href="users.php">
                    <span class="b material-icons-sharp">
                        account_circle
                    </span>
                    <h3 class="b">Users</h3>
                </a>
                <a href="">
                    <span class="b material-icons-sharp">
                        paid
                    </span>
                    <h3 class="b">Transactions</h3>
                </a>
                <a href="">
                    <span class="b material-icons-sharp">
                        notifications
                    </span>
                    <h3 class="b">Notifications</h3>
                </a>
                <a href="kyc_submission.php">
                    <span class="b material-icons-sharp">
                        notifications
                    </span>
                    <h3 class="b">KYC Verification</h3>
                </a>
                <a href="">
                    <span class="b material-icons-sharp">
                        support_agent
                    </span>
                    <h3 class="b">Support</h3>
                </a>
                <a href="">
                    <span class="b material-icons-sharp">
                        settings
                    </span>
                    <h3 class="b">Settings</h3>
                </a>
                <a href="logout.php">
                    <span class="b material-icons-sharp">
                        power_settings_new
                    </span>
                    <h3 class="b">Log out</h3>
                </a>
            </div>
        </aside>
        <main>
            <section>
                <h2>KYC Submissions</h2>
                <?php
                if (count($result) > 0) {
                    foreach ($result as $row) {
                        $submissionId = $row['user_id'];
                        $full_name = $row['full_name'];
                        $document_type = $row['document_type'];
                        $document_number = $row['document_number'];
                        $front_id_image = $row['front_id_image'];
                        $back_id_image = $row['back_id_image'];
                        $status = $row['status'];
                        $kyc_status = $row['kyc_status'];

                        echo "<div>";
                        echo "<p><strong>Full Name: </strong>$full_name</p>";
                        echo "<p><strong>Document Type: </strong>$document_type</p>";
                        echo "<p><strong>Document Number: </strong>$document_number</p>";
                        echo "<p><strong>Status: </strong>$status</p>";

                        // Display uploaded images (if available)
                        if (!empty($front_id_image) && !empty($back_id_image)) {
                            echo "<p><strong>Front ID Image: </strong><a href='$front_id_image' target='_blank'>View</a></p>";
                            echo "<p><strong>Back ID Image: </strong><a href='$back_id_image' target='_blank'>View</a></p>";
                        }

                        if (strcasecmp($kyc_status, "Pending") === 0) {
                            echo "<form method='POST' action='kyc_verification_process.php'>";
                            echo "<input type='hidden' name='user_id' value='$submissionId'>";
                            echo "<button type='submit' name='action' value='approve' class='btn'>Approve</button>";
                            echo "<button type='submit' name='action' value='reject' class='btn'>Reject</button>";
                            echo "</form>";
                        }

                        echo "</div>";
                    }
                } else {
                    echo "<p>No KYC submissions found.</p>";
                }
                ?>
            </section>

            <section id="userFormSection" style="display: none;">
                <h2>User KYC Submission Form</h2>
                <form method="post" action="kyc_verification_process.php" enctype="multipart/form-data">
                    <label for="document_type">Document Type:</label>
                    <select name="document_type" id="document_type" class="box">
                        <option value="passport">Passport</option>
                        <option value="national_id">National ID</option>
                        <option value="driving_license">Driving License</option>
                    </select><br><br>
                    <label for="document_number">Document Number:</label>
                    <input type="text" name="document_number" id="document_number" class="box"><br><br>
                    <label for="front_id_image">Front ID Image:</label>
                    <input type="file" name="front_id_image" id="front_id_image" class="box"><br><br>
                    <label for="back_id_image">Back ID Image:</label>
                    <input type="file" name="back_id_image" id="back_id_image" class="box"><br><br>
                    <input type="submit" name="submit" value="Submit" class="btn">
                </form>
            </section>
        </main>

        <script>
            // Function to show the form section when a user is clicked
            function showForm() {
                document.getElementById("userFormSection").style.display = "block";
            }
        </script>
    </div>
</body>

</html>
