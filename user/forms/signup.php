<?php 
session_start();
include("../lib/db.php"); // Assuming this file contains database connection
include("../lib/function.php");

$function = new Functions;

if (isset($_POST["signupbtn"])) {
    $MemberEmail = $_POST["MemberEmail"]; 
    $MemberPass = $function->PassSign($MemberEmail, $_POST['MemberPass']);  
    $CfmPass = $function->PassSign($MemberEmail, $_POST['CfmPass']);    
    $MemberName = $_POST["MemberName"];
    $MemberPhone = $_POST["MemberPhone"];    

    // Check if passwords match
    if ($MemberPass !== $CfmPass) {
        $_SESSION['alert'] = 'The two passwords do not match';
        header("Location: ../registration.php");
        exit();
    } else {
        // Proceed with database operations if passwords match
        $sql = "SELECT * FROM member WHERE MemberEmail = ?";
        $stmt = $db_conn->prepare($sql);
        $stmt->bind_param("s", $MemberEmail);
        $stmt->execute();
        $result = $stmt->get_result(); 
        $count = mysqli_num_rows($result);

        if ($count != 0) {
            $_SESSION['alert'] = 'This account already exists';
            header("Location: ../registration.php");
            exit();
        } else {
            $MemberJoined = date('Y-m-d H:i:s');
            $sql = "INSERT INTO member (MemberEmail, MemberPass, MemberName, MemberPhone, MemberType, isUp, MemberJoined) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db_conn->prepare($sql);
            $stmt->bind_param("sssssss", $MemberEmail, $MemberPass, $MemberName, $MemberPhone, $MemberType, $isUp, $MemberJoined); // Corrected method
            $MemberType = 'email';
            $isUp = '1';
            $stmt->execute();
            $_SESSION['alert'] = 'Record saved successfully!';
            header("Location: ../registration.php");
            exit();
        }
    }
}
?>