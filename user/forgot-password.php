<?php
include("lib/db.php");
include("lib/function.php");

$function = new Functions;

$TITLE = "Forgot Your Password?";
$SiteUrl = "http://localhost:80/FYP-Project";

$msg = isset($_GET["msg"]) ? $_GET["msg"] : ""; // Check if 'msg' exists in the GET request
$PassEmail = "";
$content = "";

if(isset($_POST["BtnPass"]))
{
	$PassEmail = $_POST["PassEmail"];

	$msg = array();

	$check_query = mysqli_query($db_conn, "SELECT MemberID, MemberName FROM member WHERE MemberLogin='".mysqli_escape_string($db_conn, $PassEmail)."' AND isUp='1'");
	$check_num = mysqli_num_rows($check_query);

	if($check_num == 0)
		$msg[] = "Invalid Email Address.";
	else
	{
		$check_row = mysqli_fetch_array($check_query);
		
		$MemberID = $check_row["MemberID"];
		$MemberName = stripslashes($check_row["MemberName"]);
		
		$encodedToken = substr_replace($PassEmail, $MemberID."#".date("His"), 4, 0);

$resetLink = "$SiteUrl/reset-password.php?token=$encodedToken";

$custom_msg = "$resetLink######$PassEmail";
		
		$function->send_email("2", $MemberName, $PassEmail, $custom_msg);
		
		mysqli_query($db_conn, "UPDATE member SET MemberReset=DATE_ADD(NOW(), INTERVAL 8 HOUR) WHERE MemberID='$MemberID'");
		
		$content .= "<script>self.location = '$SiteUrl/forgotpass/success/'</script>";
	}
	
	if(!empty($msg))
		$type = "error";
}

if(!empty($msg))
{
	switch($msg)
	{
		case "success": $type = "success"; $msg = "An email have sent to your email address."; break;
		case "expired": $type = "error"; $msg = "The link is not longer valid."; break;
		case "invalid": $type = "error"; $msg = "Invalid link."; break;
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
                if (!empty($msg)) {
                    if (is_array($msg)) {
                        foreach ($msg as $message) {
                            echo "<p>$message</p>";
                        }
                    } else {
                        echo "<p>$msg</p>";
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
