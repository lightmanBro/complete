<?php
session_start();
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: ../../index.php");
    die();
}
    include '../../config.php';
$query = mysqli_query($conn, "SELECT * FROM users WHERE email='{$_SESSION['SESSION_EMAIL']}'");

if (mysqli_num_rows($query) > 0) {
    $fetch = mysqli_fetch_assoc($query);
}
if (isset($_POST['submit'])) {
    $sql = "SELECT * FROM users WHERE email='{$_SESSION['SESSION_EMAIL']}'";
    $statement = $conn->prepare($sql);
    $statement->bind_param('i', $_SESSION['SESSION_EMAIL']);
    $statement->execute();
    $result = $statement->get_result();
    $row = $result->fetch_assoc();

    if (! empty($row)) {
        $hashedPassword = $row["password"];
        $password = PASSWORD_HASH($_POST["newPassword"], PASSWORD_DEFAULT);
        if (password_verify($_POST["currentPassword"], $hashedPassword)) {
            $sql = "UPDATE users set password=? WHERE userId=?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('si', $password, $_SESSION['SESSION_EMAIL']);
            $statement->execute();
            $message = "Password Changed";
        } else
            $message = "Current Password is not correct";
    }
}
?>