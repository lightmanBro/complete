<?php
session_start();
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

include '../server.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the query to include the search condition
if (!empty($search)) {
    $usersQuery = "SELECT * FROM users WHERE first_name LIKE '%$search%' OR middle_name LIKE '%$search%' OR last_name LIKE '%$search%'";
} else {
    $usersQuery = "SELECT * FROM users";
}

$usersResult = $pdo->query($usersQuery);

// Build the HTML for the user table rows
$html = '';
while ($row = $usersResult->fetch(PDO::FETCH_ASSOC)) {
    $html .= '<tr>
        <td>' . $row['id'] . '</td>
        <td>' . $row['first_name'] . '</td>
        <td>' . $row['middle_name'] . '</td>
        <td>' . $row['last_name'] . '</td>
        <td>' . $row['username'] . '</td>
        <td>' . $row['level'] . '</td>
        <td>' . $row['email'] . '</td>
        <td>' . $row['kyc_status'] . '</td>
        <td>
            <div class="dropdown">
                <button class="dropbtn">Actions</button>
                <div class="dropdown-content">
                <button class="action-btn" onclick="showDetails(' . $row['id'] . ')">View</button>
                    <button class="action-btn" onclick="showDetails(' . $row['id'] . ')">More</button>
                    <button class="action-btn" onclick="deleteUser(' . $row['id'] . ')">Delete</button>
                </div>
            </div>
        </td>
    </tr>';
}

echo $html;
?>
