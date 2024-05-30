<?php 
session_start();
include("lib/db.php"); // Assuming this file contains database connection
include("lib/function.php");

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
        header("Location: registration.php");
        exit();
    } else {
        // Proceed with database operations if passwords match
        $sql = "SELECT * FROM member WHERE MemberEmail = '$MemberEmail'";
        $query = $db_conn->query($sql);
        $count = mysqli_num_rows($query);

        if ($count != 0) {
            $_SESSION['alert'] = 'This account already exists';
            header("Location: registration.php");
            exit();
        } else {
            $MemberJoined = date('Y-m-d H:i:s');
            $MemberType = 'email';
            $MemberLogin = $MemberEmail;
            $isUp = '1';
            $sql = "INSERT INTO member (MemberEmail, MemberPass, MemberName, MemberPhone, MemberType, isUp, MemberJoined, MemberLogin) 
                VALUES ('$MemberEmail', '$MemberPass', '$MemberName', '$MemberPhone', '$MemberType', '$isUp', '$MemberJoined', '$MemberLogin')";
            $query = $db_conn->query($sql);
            $_SESSION['alert'] = 'Record saved successfully!';
            header("Location: registration.php");
            exit();
        }
    }
}
?>
