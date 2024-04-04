<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['MemberEmail'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../registration.php");
    exit(); // Exit to prevent further execution
}

$errors = array(); 

// Include database connection
include("../lib/db.php"); // Assuming this includes your actual database connection script
include("../lib/function.php");

$function = new Functions;

// REGISTER USER
if (isset($_POST['BtnUpdatePass'])) {
    // receive all input values from the form
    $MemberPass = $_POST['MemberPass'];
    $CfmPass = $_POST['CfmPass'];
    $memberEmail = $_SESSION["MemberEmail"];

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($MemberPass)) { 
        array_push($errors, "Password is required");
    }
    if ($MemberPass != $CfmPass) {
        array_push($errors, "The two passwords do not match");
    }

    if (count($errors) == 0) {
        // Update user's profile in the database
        $sql = "UPDATE member SET MemberPass = ? WHERE MemberEmail = ?";
        $stmt = $db_conn->prepare($sql);

        // Check if the prepare() call succeeded
        if ($stmt === false) {
            $_SESSION["error_message"] = "Error preparing statement: " . $db_conn->error;
            header("Location: ../member_password.php");
            exit();
        }

        // Bind parameters and execute statement
        $hashedPassword = password_hash($MemberPass, PASSWORD_DEFAULT);
        $stmt->bind_param("ss", $hashedPassword, $memberEmail);
        $result = $stmt->execute();

        // Check if the update was successful
        if ($result) {
            $_SESSION['success'] = "Password updated successfully";
            echo "<script>alert('Password updated successfully');</script>";
            header('location: ../member_password.php');
            exit(); // Exit after redirection
        } else {
            $_SESSION["error_message"] = "Error updating password: " . $db_conn->error;
            header("Location: ../member_password.php");
            exit();
        }
    } else {
        // Display errors using JavaScript alerts
        foreach ($errors as $error) {
            echo "<script>alert('$error');</script>";
        }
    }
}
?>