<?php
session_start();
include("lib/db.php"); // Include database connection

if (isset($_POST['submit'])) {
    $token = $_POST['token'];
    $password = $_POST['password'];

    // Hash the new password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update password in database
    $updateSql = "UPDATE users SET password='$hashedPassword', reset_token=NULL WHERE reset_token='$token'";
    $db_conn->query($updateSql);

    $_SESSION['message'] = "Password reset successful. You can now login with your new password.";
    header("Location: login.php");
    exit();
} else {
    $_SESSION['message'] = "Invalid request. Please try again.";
    header("Location: forgot-password.php");
    exit();
}
?>