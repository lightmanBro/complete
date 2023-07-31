<?php
session_start();
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

include '../config.php';

// Fetch users from the database
$usersQuery = "SELECT * FROM users";
$usersResult = mysqli_query($conn, $usersQuery);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Users</title>
    <!-- Add your CSS styling and any necessary scripts here -->
</head>
<body>
    <h1>Admin Dashboard - Users</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>KYC Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($usersResult)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['kyc_status']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
