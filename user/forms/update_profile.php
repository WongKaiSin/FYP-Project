<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['MemberEmail'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../registration.php");
    exit();
}

// Include database connection
include("../lib/db.php"); // Assuming this includes your actual database connection script

// Check if form is submitted
if (isset($_POST['BtnUpdateProfile'])) {
    // Prepare data for insertion
    $memberName = $_POST["MemberName"];
    $memberPhone = $_POST["MemberContact"];
    $memberEmail = $_SESSION["MemberEmail"];

    // Update user's profile in the database
    $sql = "UPDATE member SET MemberName = ?, MemberPhone = ? WHERE MemberEmail = ?";
    $stmt = $db_conn->prepare($sql);

    // Check if the prepare() call succeeded
    if ($stmt === false) {
        $_SESSION["error_message"] = "Error preparing statement: " . $db_conn->error;
        header("Location: ../member_profile.php");
        exit();
    }

    // Bind parameters and execute statement
    $stmt->bind_param("sss", $memberName, $memberPhone, $memberEmail);
    $result = $stmt->execute();

    // Check if the execution succeeded
    if ($result === false) {
        $_SESSION["error_message"] = "Error updating profile: " . $stmt->error;
    } else {
        $_SESSION["success_message"] = "Profile updated successfully.";
    }

    // Close statement
    $stmt->close();
}

// Close database connection
$db_conn->close();

// Redirect back to profile page
header("Location: ../member_profile.php");
exit();
?>