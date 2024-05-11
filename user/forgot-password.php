<?php
include("lib/db.php");

$TITLE = "Forgot Your Password?";
$SiteTitle = "YourSiteTitle";
$SiteUrl = "http://yourwebsite.com"; 

$msg = isset($_POST["msg"]) ? $_POST["msg"] : [];
$PassEmail = isset($_POST["PassEmail"]) ? $_POST["PassEmail"] : "";
$content = "";

if(isset($_POST["BtnPass"])) {
    $PassEmail = $_POST["PassEmail"];

    $msg = [];

    // Sanitize input
    $PassEmail = mysqli_real_escape_string($db_conn, $PassEmail);

    $check_query = mysqli_prepare($db_conn, "SELECT MemberID, MemberName FROM member WHERE MemberEmail=? AND isUp='1'");
    mysqli_stmt_bind_param($check_query, "s", $PassEmail);
    mysqli_stmt_execute($check_query);
    mysqli_stmt_store_result($check_query);
    $check_num = mysqli_stmt_num_rows($check_query);

    if($check_num == 0) {
        $msg[] = "Invalid Email Address.";
    } else {
        mysqli_stmt_bind_result($check_query, $MemberID, $MemberName);
        mysqli_stmt_fetch($check_query);

        $encodedToken = substr_replace($PassEmail, $MemberID."#".date("His"), 4, 0);

        // Store encoded token in the database
        $encodedToken = mysqli_real_escape_string($db_conn, $encodedToken);
        mysqli_query($db_conn, "UPDATE member SET MemberReset='$encodedToken' WHERE MemberID='$MemberID'");

        // Send email with reset link
        $resetLink = "http://yourwebsite.com/reset-password.php?token=$encodedToken";
        $to = $PassEmail;
        $subject = "Password Reset";
        $message = "Click the link below to reset your password:\n\n$resetLink";
        $headers = "From: your@example.com" . "\r\n" .
           "Reply-To: your@example.com" . "\r\n";


        if (mail($to, $subject, $message, $headers)) {
            $msg[] = "Password reset link has been sent to your email.";
        } else {
            $msg[] = "Failed to send the password reset email. Please try again later.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $TITLE; ?></title>
</head>
<body>
    <h2>Forgot Password</h2>
    <div class="box-center">
        <div class="col large-5">
            <div class="row">
                <h3 class="mb-30"><span>Forgot Password</span></h3>
                <?php 
                if(!empty($msg)) {
                    foreach ($msg as $message) {
                        echo "<p>$message</p>";
                    }
                }
                ?>
                <p>Please enter your email address used for login. You will receive a link to create a new password via email.</p>
                <form name="theForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <label>Email Address</label>
                    <input type="email" name="PassEmail" value="<?php echo htmlspecialchars($PassEmail); ?>" required />
                    <input type="submit" name="BtnPass" value="Reset Password" class="button primary lowercase" />
                </form>
            </div>
        </div>
    </div>
</body>
</html>
