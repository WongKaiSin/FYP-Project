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
    $sql = "UPDATE member SET MemberName = ?, MemberPhone = ? WHERE MemberEmail = ?";
    $stmt = $db_conn->prepare($sql);

    // Check if the prepare() call succeeded
    if ($stmt === false) {
        $_SESSION["alert"] = "Error preparing statement: " . $db_conn->error;
        header("Location: ../member_profile.php");
        exit();
    }

    // Bind parameters and execute statement
    $stmt->bind_param("sss", $MemberName, $MemberPhone, $MemberEmail);
    $result = $stmt->execute();

    if ($result) {
        // Get the current timestamp
        $MemberReset = date('Y-m-d H:i:s');

        // Update the MemberReset in the database
        $updateSql = "UPDATE member SET MemberReset = ? WHERE MemberEmail = ?";
        $updateStmt = $db_conn->prepare($updateSql);

        if ($updateStmt === false) {
            $_SESSION["alert"] = "Error preparing update statement: " . $db_conn->error;
            header("Location: ../member_profile.php");
            exit();
        }

        // Bind parameters and execute update statement
        $updateStmt->bind_param("ss", $MemberReset, $MemberEmail);
        $updateResult = $updateStmt->execute();

        if ($updateResult) {
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
