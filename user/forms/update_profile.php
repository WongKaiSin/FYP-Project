<?php
session_start();
include("../lib/db.php");

// Check if user is logged in
if (!isset($_SESSION['MemberEmail'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../registration.php");
    exit();
}

// Check if form is submitted
if (isset($_POST['BtnUpdateProfile'])) {
    // Prepare data for insertion
    $MemberName = $_POST["MemberName"];
    $MemberPhone = $_POST["MemberPhone"];
    $MemberEmail = $_SESSION["MemberEmail"];

    // Update user's profile in the database
    $sql = "UPDATE member SET MemberName = '$MemberName', MemberPhone = '$MemberPhone' WHERE MemberEmail = '$MemberEmail'";
    $query = $db_conn->query($sql);

    if ($query) {
        // Get the current timestamp
        $MemberReset = date('Y-m-d H:i:s');

        // Update the MemberReset in the database
        $updateSql = "UPDATE member SET MemberReset = '$MemberReset' WHERE MemberEmail = '$MemberEmail'";
        $updateQuery = $db_conn->query($updateSql);

        if ($updateQuery) {
            $_SESSION['alert'] = 'Profile updated successfully.';
            header('Location: ../member_profile.php');
            exit(); // Exit after redirection
        } else {
            $_SESSION['alert'] = 'Error updating profile';
            header("Location: ../member_profile.php");
            exit();
        }
    } else {
        $_SESSION['alert'] = 'Error updating profile';
        header("Location: ../member_profile.php");
        exit();
    }
}
?>