<?php 
session_start();
include("../lib/db.php"); // Assuming this file contains database connection
include("../lib/function.php");

$function = new Functions;

if(isset($_POST["loginbtn"])) {
    $MemberEmail = $_POST['MemberEmail'];
    $MemberPass = $function->PassSign($MemberEmail, $_POST['MemberPass']);  

    $sql = "SELECT * FROM member WHERE MemberEmail = ? AND MemberPass = ? AND isUp='1'";
    $stmt = $db_conn->prepare($sql);
    $stmt->bind_param("ss", $MemberEmail, $MemberPass);
    $stmt->execute();
    $result = $stmt->get_result(); 
 
    if($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Get the current timestamp
        $MemberLoginTime = date('Y-m-d H:i:s');
        
        // Update the MemberLoginTime in the database
        $updateSql = "UPDATE member SET MemberLoginTime = ? WHERE MemberEmail = ?";
        $updateStmt = $db_conn->prepare($updateSql);
        $updateStmt->bind_param("ss", $MemberLoginTime, $MemberEmail);
        $updateStmt->execute();
        
        // Set session and redirect
        $_SESSION['MemberEmail'] = $row['MemberEmail'];
        echo "<script>alert('You are now logged in');</script>";
        echo "<script>window.location.href='../index.php';</script>";
        exit();
    } else {
        $_SESSION['alert'] = 'Invalid username or password';
        header("Location: ../registration.php");
        exit();
    }
}
?>
