<?php 
session_start();
include("lib/db.php"); // Assuming this file contains database connection
include("lib/function.php");

$function = new Functions;

if(isset($_POST["loginbtn"])) {
    $MemberEmail = $_POST['MemberEmail'];
    $MemberPass = $function->PassSign($MemberEmail, $_POST['MemberPass']);  

    $sql = "SELECT * FROM member WHERE MemberEmail = '$MemberEmail' AND MemberPass = '$MemberPass' AND isUp='1'";
    $query = $db_conn->query($sql); 

    if($query->num_rows == 1) {
        $row = $query->fetch_assoc();
        $MemberID = $row["MemberID"];
        
        // Get the current timestamp
        $MemberLoginTime = date('Y-m-d H:i:s');
        
        // Update the MemberLoginTime in the database
        $updateSql = "UPDATE member SET MemberLoginTime = '$MemberLoginTime' WHERE MemberEmail = '$MemberEmail'";
        $query = $db_conn->query($updateSql);

        // Check the CartID
        $cart_query = mysqli_query($db_conn, "SELECT CartID FROM cart WHERE (`CartSession`='".$CurrCart."' OR MemberID='$MemberID')");
        $cart_num = mysqli_num_rows($cart_query);
        
        // If new login don't have CartSession
        if($cart_num == 0)
        {
            mysqli_query($db_conn, "INSERT INTO cart (`CartSession`, `MemberID`, `CartAddDate`) VALUES ('$CurrCart', '$MemberID', NOW())");
            $CartID = mysqli_insert_id($db_conn);
        }
        else
        {
            // update cart
            mysqli_query($db_conn, "UPDATE cart SET MemberID='$MemberID' WHERE CartSession='$CurrCart'");
            mysqli_query($db_conn, "UPDATE cart SET CartSession='$CurrCart' WHERE MemberID='$MemberID'");
        }
        
        // Set session and redirect
        $_SESSION['MemberEmail'] = $row['MemberEmail'];
        $_SESSION['MemberID'] = $row["MemberID"];
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid username or password";
    header("Location: registration.php");
    exit();
    }
}
?>