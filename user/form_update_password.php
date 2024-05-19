<?php 
session_start();
include("lib/db.php"); // Assuming this file contains database connection
include("lib/function.php");

$function = new Functions;

// Check if user is logged in
if (!isset($_SESSION['MemberEmail'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: registration.php");
    exit(); // Exit to prevent further execution
}

// REGISTER USER
if (isset($_POST['BtnUpdatePass'])) {
    // receive all input values from the form
    $MemberEmail = $_SESSION["MemberEmail"]; // Retrieve MemberEmail from session
    $MemberPass = $function->PassSign($MemberEmail, $_POST['MemberPass']);  
    $CfmPass = $function->PassSign($MemberEmail, $_POST['CfmPass']); 

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if ($MemberPass !== $CfmPass) {
        $_SESSION['alert'] = 'The two passwords do not match';
        header("Location: member_password.php");
        exit();
    } else {
        // Update user's profile in the database
        $sql = "UPDATE member SET MemberPass = '$MemberPass' WHERE MemberEmail = '$MemberEmail' AND isUp='1'";
        $query = $db_conn->query($sql);

        if ($query) {
            // Get the current timestamp
            $MemberReset = date('Y-m-d H:i:s');

            // Update the MemberReset in the database
            $updateSql = "UPDATE member SET MemberReset = '$MemberReset' WHERE MemberEmail = '$MemberEmail'";
            $updateQuery = $db_conn->query($updateSql);

            if ($updateQuery) {
                $_SESSION['alert'] = 'Password updated successfully';
                header('Location: member_password.php');
                exit(); // Exit after redirection
            } else {
                $_SESSION['alert'] = 'Error updating reset time';
                header("Location: member_password.php");
                exit();
            }
        } else {
            $_SESSION['alert'] = 'Error updating password';
            header("Location: member_password.php");
            exit();
        }
    }
}
?>