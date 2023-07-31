<?php
session_start();
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

include '../server.php';

$query = $pdo->query("SELECT * FROM admin WHERE email='{$_SESSION['SESSION_EMAIL']}'");
$fetch = $query->fetch();

// Fetch user data
$userQuery = $pdo->query("SELECT COUNT(*) as total_users FROM user_credentials");
$userData = $userQuery->fetch();
$totalUsers = $userData['total_users'];
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

    <title>Tamopie Admin Dashboard</title>
</head>

<body>
    <div class="container">
        <aside class="">
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
            <div class="firstheading">
                <h2 class="h2 hr">Welcome! <?php echo $fetch['name']; ?>.</h2><br><br>
                <div class="profile">
                    <button class="profile-btn" onclick="toggleProfileDropdown()">
                        <img class="profile-image" src="<?php echo $fetch['profile_image']; ?>" alt="Profile Image">
                    </button>
                    <div class="profile-content" id="profileContent">
                        <ul>
                            <li><a href="#">Profile Settings</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <canvas id="userChart"></canvas>
        </main>
    </div>

    <script src="./forms.js"></script>
    <script src="./userSettings.js"></script>
    <script src="./script.js"></script>
    <script>
        // JavaScript code
        const profileImage = document.querySelector('.profile-image');
        const profileContent = document.querySelector('.profile-content');

        profileImage.addEventListener('click', () => {
            profileContent.classList.toggle('show');
        });
    </script>
    <script>
        // Fetch total users from PHP
        const totalUsers = <?php echo $totalUsers; ?>;

        // Initialize chart data
        const chartData = {
            labels: ['Registered Users'],
            datasets: [{
                label: 'Number of Users',
                data: [totalUsers],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
            }]
        };

        // Render chart
        const userChart = document.getElementById('userChart').getContext('2d');
        new Chart(userChart, {
            type: 'bar',
            data: chartData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0,
                    }
                }
            }
        });
    </script>
</body>
</html>
