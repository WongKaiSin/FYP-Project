<?php 
session_start();
include("../lib/db.php"); // Assuming this file contains database connection
include("../lib/function.php");

$function = new Functions;

if(isset($_POST["loginbtn"])) {
    $MemberEmail = $_POST['MemberEmail'];
    $MemberPass = $function->PassSign($MemberEmail, $_POST['MemberPass']);  

    $sql = "SELECT * FROM member WHERE MemberEmail = '$MemberEmail' AND MemberPass = '$MemberPass' AND isUp='1'";
    $query = $db_conn->query($sql); 

    if($query->num_rows == 1) {
        $row = $query->fetch_assoc();
        
        // Get the current timestamp
        $MemberLoginTime = date('Y-m-d H:i:s');
        
        // Update the MemberLoginTime in the database
        $updateSql = "UPDATE member SET MemberLoginTime = '$MemberLoginTime' WHERE MemberEmail = '$MemberEmail'";
        $query = $db_conn->query($updateSql); 
        
        // Set session and redirect
        $_SESSION['MemberEmail'] = $row['MemberEmail'];
        $_SESSION["MemberID"] = $row["MemberID"];
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['alert'] = 'Invalid username or password';
        header("Location: ../registration.php");
        exit();
    }
}
?>