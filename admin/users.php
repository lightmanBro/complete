<?php
session_start();
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

include '../server.php';

// Fetch users from the database
$usersQuery = "SELECT * FROM user_credentials";
$query = $pdo->query("SELECT * FROM admin WHERE email='{$_SESSION['SESSION_EMAIL']}'");

if ($query->rowCount() > 0) {
    $fetch = $query->fetch();
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the user_credentials query to include the search condition
if (!empty($search)) {
    $usersQuery = "SELECT * FROM user_credentials WHERE name LIKE '%$search%'";
} else {
    $usersQuery = "SELECT * FROM user_credentials";
}

$usersResult = $pdo->query($usersQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="fontsawesome/css/all.css">
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="color.css">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/sendmoney.css">
    <title>Admin Dashboard - Users</title>
    
    <style>
.action-btn {
  background-color: #4CAF50;
  color: white;
  padding: 8px 8px;
  border: none;
  border-radius: 2px;
  cursor: pointer;
}

.action-btn:hover {
  background-color: #4CAF50;
}

        .dropdown {
  position: relative;
  display: inline-block;
}

.dropbtn {
  background-color: #4CAF50;
  color: white;
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 120px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
  z-index: 1;
}

.dropdown-content a, .dropdown-content button {
  color: black;
  padding: 8px 16px;
  text-decoration: none;
  display: block;
  cursor: pointer;
}

.dropdown-content a:hover, .dropdown-content button:hover {
  background-color: #f1f1f1;
}

.dropdown:hover .dropdown-content {
  display: block;
}

.dropdown:hover .dropbtn {
  background-color: #45a049;
}

         .search-container{
    display: flex;
    align-items: center;
    margin-bottom: 30px;
  }
  
  #searchInput{
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-right: 10px;
    width: 200px;
  }
  
  #searchButton{
    padding: 8px 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  #searchButton:hover{
    background-color: #45a049;
  }
  
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .popup-form {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            z-index: 9999;
        }
        .popup-form h2 {
            margin-top: 0;
        }
        .close-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  cursor: pointer;
  font-size: 20px;
  color: red;
  transition: color 0.3s ease;
}

.close-btn:hover {
  color: red;
}
        .edit-form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: fixed;
        top: 10%;
        left: 50%;
        transform: translate(-20%, -20%);
        background-color: #fff;
        padding: 10px;
        border-radius: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        
    }
    .form-container{
   min-height: 30vh;
   background-color: var(--light-bg);
   display: flex;
   align-items: center;
   justify-content: start;
   padding:2px;
   padding-left:50px;
   position: absolute;
   
}
.form-container form{
   padding:5px;
   padding-top:1px;
   background-color:#fff ;
   box-shadow: var(--box-shadow);
   text-align: center;
   width: 450px;
   border-radius: 2px;
   border: 1px  #fff;
}

.form-container form h3{
   margin-bottom: 10px;
   margin-top: 20px;
   font-size: 15px;
   color: #5ea536;
   text-transform: uppercase;
   
}

.field-icon {
   float: right;
   margin-left: -25px;
   margin-top: -25px;
   position: relative;
   z-index: 2;
 }
.form-container form .box{
   width: 100%;
   border-radius: 5px;
   padding:12px 14px;
   font-size: 18px;
   color:var(--black);
   margin:10px 0;
   background-color: var(--light-bg);
   border: 1px solid #5ea536;
}

    </style>
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
            <a href="dashboard.php" class="">
                <span class="b material-icons-sharp">grid_view</span>
                <h3 class="b">Dashboard</h3>
            </a>
            <a href="" class="active">
                <span class="b material-icons-sharp">account_circle</span>
                <h3 class="b">Users</h3>
            </a>
            <a href="">
                <span class="b material-icons-sharp">paid</span>
                <h3 class="b">Transactions</h3>
            </a>
            <a href="">
                <span class="b material-icons-sharp">notifications</span>
                <h3 class="b">Notifications</h3>
            </a>
            <a href="kyc_submission.php">
                <span class="b material-icons-sharp">notifications</span>
                <h3 class="b">KYC Verification</h3>
            </a>
            <a href="">
                <span class="b material-icons-sharp">support_agent</span>
                <h3 class="b">Support</h3>
            </a>
            <a href="">
                <span class="b material-icons-sharp">settings</span>
                <h3 class="b">Settings</h3>
            </a>
            <a href="logout.php">
                <span class="b material-icons-sharp">power_settings_new</span>
                <h3 class="b">Log out</h3>
            </a>
        </div>
    </aside>
    <main>
            <div class="firstheading">
                <h2 class="h2 hr">Welcome! <?php echo $fetch['name']; ?>.</h2><br><br>
                <div class="profile">
                    <!-- Profile dropdown menu -->
                </div>
            </div>
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search by first" oninput="searchUser()">
                <button id="searchButton" onclick="searchUser()">Search</button>
            </div>
            <table id="userTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Username</th>
                        <th>Level</th>
                        <th>Email</th>
                        <th>KYC Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
    <?php while ($row = $usersResult->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo $row['user_id']; ?></td>
            <td><?php echo $row['first_name']; ?></td>
            <td><?php echo $row['middle_name']; ?></td>
            <td><?php echo $row['last_name']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['level']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['kyc_status']; ?></td>
            <td>
                <div class="dropdown">
                    <button class="dropbtn">Actions</button>
                    <div class="dropdown-content">
                        <button class="action-btn" onclick="showDetails(<?php echo $row['user_id']; ?>)">View</button>
                        <button class="action-btn" onclick="deleteUser(<?php echo $row['user_id']; ?>)">Delete</button>
                        <button class="action-btn" onclick="showEditForm(<?php echo $row['user_id']; ?>)">Edit</button>
                    </div>
                </div>
            </td>
        </tr>
    <?php endwhile; ?>
</tbody>

    </table>
    </main>
    <script>
        // JavaScript code
        const profileImage = document.querySelector('.profile-image');
        const profileContent = document.querySelector('.profile-content');

        profileImage.addEventListener('click', () => {
            profileContent.classList.toggle('show');
        });
    </script>
    <script>
        // Function to delete a user and their submitted documents
        function deleteUser(userId) {
            if (confirm("Are you sure you want to delete this user? This action cannot be undone.")) {
                // Send an AJAX request to delete the user
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_user.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Handle the response from the server
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Reload the page or perform any other desired action
                            window.location.reload();
                        } else {
                            // Display an error message or handle the error case
                            alert('Failed to delete user. Please try again.');
                        }
                    }
                };
                xhr.send('userId=' + userId);
            }
        }
    </script>
    <script>
        // Function to show the edit user form
        function showEditForm(id) {
            // Fetch user details via AJAX request based on the provided ID
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_user_details.php?id=' + id, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var userDetails = JSON.parse(xhr.responseText);

                    // Populate the form fields with user details
                    document.getElementById("editId").value = userDetails.id;
                    document.getElementById("editName").value = userDetails.name;
                    document.getElementById("editUsername").value = userDetails.username;
                    document.getElementById("editLevel").value = userDetails.level;
                    document.getElementById("editEmail").value = userDetails.email;
                    document.getElementById("editKycStatus").value = userDetails.kyc_status;
                    document.getElementById("editAddress").value = userDetails.address;

                    // Show the edit form
                    document.getElementById("editForm").style.display = "block";
                }
            };
            xhr.send();
        }

        // Function to hide the edit user form
        function hideEditForm() {
            document.getElementById("editForm").style.display = "none";
        }
    </script>
    <script>
        // Function to show user details in a popup
        function showDetails(id) {
            // Fetch user details via AJAX request based on the provided ID
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_user_details.php?id=' + id, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var userDetails = JSON.parse(xhr.responseText);

                    // Populate the popup with user details
                    document.getElementById("detailsId").textContent = userDetails.id;
                    document.getElementById("detailsName").textContent = userDetails.name;
                    document.getElementById("detailsUsername").textContent = userDetails.username;
                    document.getElementById("detailsLevel").textContent = userDetails.level;
                    document.getElementById("detailsEmail").textContent = userDetails.email;
                    document.getElementById("detailsKycStatus").textContent = userDetails.kyc_status;
                    document.getElementById("detailsAddress").textContent = userDetails.address;

                    // Show the popup
                    document.getElementById("detailsPopup").style.display = "block";
                }
            };
            xhr.send();
        }

        // Function to close the user details popup
        function closeDetails() {
            document.getElementById("detailsPopup").style.display = "none";
        }
    </script>
    <script>
        // Function to search for users based on name
        function searchUser() {
            var searchInput = document.getElementById('searchInput');
            var searchValue = searchInput.value.trim();

            if (searchValue !== '') {
                // Send an AJAX request to fetch the filtered users
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'filter_users.php?search=' + encodeURIComponent(searchValue), true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Update the user table with the filtered users
                        document.getElementById('userTable').innerHTML = xhr.responseText;
                        applyStylingToActionButtons(); // Reapply the styling to action buttons
                    }
                };
                xhr.send();
            } else {
                // If the search input is empty, fetch all users
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'filter_users.php', true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Update the user table with all users
                        document.getElementById('userTable').innerHTML = xhr.responseText;
                        applyStylingToActionButtons(); // Reapply the styling to action buttons
                    }
                };
                xhr.send();
            }
        }

        // Function to apply styling to action buttons
        function applyStylingToActionButtons() {
            var actionButtons = document.getElementsByClassName('action-btn');
            for (var i = 0; i < actionButtons.length; i++) {
                var button = actionButtons[i];
                button.style.backgroundColor = '#4CAF50';
                button.style.color = 'white';
                button.style.padding = '8px 8px';
                button.style.border = 'none';
                button.style.borderRadius = '2px';
                button.style.cursor = 'pointer';
            }
        }

        // Call applyStylingToActionButtons initially to apply the styling to action buttons
        applyStylingToActionButtons();
    </script>
</body>
</html>