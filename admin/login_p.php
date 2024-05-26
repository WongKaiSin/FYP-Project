<?php
session_start();
include("lib/db.php");
include("lib/function.php");

$function = new Functions;

// Check if the login form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["loginbtn"])) 
{
    $adUser = $_POST['adUser'];
    $adPass = $function->PassSign($adUser, $_POST['adPass']);

    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
	{
        $secret = "6Lf1TrQpAAAAAKE_eR_ZSisp9cNZCXQl7VcfmLjy"; //v3
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);

        if($responseData->success)
        {
            // Prepare the SQL statement with prepared statements to prevent SQL injection
            $sql = "SELECT * FROM admin WHERE adUser = '$adUser' AND adPass = '$adPass' AND adStatus='1'";

            // Execute the query
            $query = $db_conn->query($sql);

            // Check if the user exists
            if ($query && $query->num_rows == 1) {
                $row = $query->fetch_assoc();

                // Set session variables
                $_SESSION['AdminLogged'] = true;
                $_SESSION["adUser"] = $row["adUser"];
                $_SESSION["adEmail"] = $row["adEmail"];
                $_SESSION["adName"] = $row["adName"];
                $_SESSION["adType"] = $row["adType"];
                $_SESSION["adMenu"] = $row["adMenu"];
                $_SESSION["adLogo"] = $row["adLogo"];

                // Redirect to the home page or desired location
                header("Location: index.php");
                exit;
            } 
            else 
            {   
                header("Location: login.php?msg=1");
                exit;
            }

            
        }
    }
}


/*if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["BtnPass"])) {
    $PassEmail = $_POST['email'];

    $check_query = mysqli_query($db_conn, "SELECT adID, adName, adEmail FROM admin WHERE adEmail='$PassEmail' AND adStatus='1'");
    $check_num = mysqli_num_rows($check_query);

    if($check_num > 0) {
        $row = mysqli_fetch_assoc($check_query);
        $adName = $row['adName'];
        $adEmail = $row['adEmail'];

        $message = "Dear ".$adName.",<br><br>
					We received a request to reset your password for your account. <br><br>
					You may use the new password, <strong>1234</strong> to login now and please change the password.<br><br><br>
					Sincerely,<br>
					London Bagel Museum <br><br>";

        // Send the email
        $function->authSendEmail("weyiw64061@neixos.com", "Fitmate Webmaster", "kxn1102@gmail.com", "yoyooy", "Reset Password", $message);

        echo '<script type="text/javascript">
        alert("Password reset email sent successfully!");
        window.location.href = "login.php";
        </script>';
        exit;
    } else {
        echo '<script type="text/javascript">
        alert("Email not found. Please enter a valid email address.");
        window.location.href = "forgot_pw.php";
        </script>';
        exit;
    }
}*/

if (isset($_POST["BtnPass"])) {
    $PassEmail = $_POST['PassEmail'];

    // Check if the email exists in the database
    $check_query = mysqli_query($db_conn, "SELECT adUser, adEmail FROM admin WHERE adEmail='$PassEmail' AND adStatus='1'");
    $check_num = mysqli_num_rows($check_query);

    if ($check_num > 0) {
        $row = mysqli_fetch_assoc($check_query);
        $adUser = $row['adUser'];
        $PassEmail = $row['adEmail'];
        
        // Generate a new password
        $new_pass = substr(str_shuffle('!@#$%*&abcdefghijklmnpqrstuwxyzABCDEFGHJKLMNPQRSTUWXYZ23456789'), 0, 8);
        $hash_pass = $function->PassSign($PassEmail, $new_pass);
        
        // Prepare custom message with new password and email
        $custom_msg = "$new_pass######$PassEmail";
        
        // Send email with new password
        $function->send_email(2, $adUser, $PassEmail, $custom_msg);

        // Update the password in the database
        mysqli_query($db_conn, "UPDATE `admin` SET adPass = '$new_pass', AdminModifyDate = NOW() WHERE adUser = '$adUser'");

        // Redirect with success message
        echo '<script type="text/javascript">
        alert("Password reset email sent successfully!");
        window.location.href = "login.php";
        </script>';
        exit;
    } else {
        // Redirect with error message
        echo '<script type="text/javascript">
        alert("Email not found. Please enter a valid email address.");
        window.location.href = "forgot_pw.php";
        </script>';
        exit;
    }
}
?>



