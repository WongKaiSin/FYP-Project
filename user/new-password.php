<?
$TITLE = "Create New Password";
$SEO_TITLE = "$TITLE - $SiteTitle";

$PassText = $_GET["id"];
$msg = $_GET["msg"];
$content = "";
$error = 0;

$unlock = codeUnlock($PassText);
$unlock = explode("#", $unlock);
$unlock = current($unlock);
$MemberID = substr($unlock, 4);

$check_query = mysqli_query($db_conn, "SELECT MemberLogin, MemberReset FROM js_store_member WHERE MemberID='".$MemberID."'");
$check_num = mysqli_num_rows($check_query);

if($check_num == 0)
{
	$error = 1;
	$content .= "<script>self.location = '$SiteUrl/forgotpass/invalid/'</script>";
}
else
{
	$check_row = mysqli_fetch_array($check_query);
	
	$MemberLogin = $check_row["MemberLogin"];
	$MemberReset = $check_row["MemberReset"];
	
	$expiredTime = date("Y-m-d H:i:s", strtotime("+20 minutes", strtotime($MemberReset)));

	if($expiredTime < date("Y-m-d H:i:s"))
	// if($expiredTime < $MemberReset)
	{
		$error = 1;
		
		$content .= "<script>self.location = '$SiteUrl/forgotpass/expired/'</script>";
	}
}

if(isset($_POST["BtnNew"]) && $error == 0)
{
	$PassNew = $_POST["PassNew"];
	$PassNewConfirm = $_POST["PassNewConfirm"];
	
	if(!empty($PassNew))
	{
		if($PassNew == $PassNewConfirm)
		{
			mysqli_query($db_conn, "UPDATE js_store_member SET MemberPass='".mysqli_escape_string($db_conn, PassSign($MemberLogin, $PassNew))."', MemberReset='' WHERE MemberID='$MemberID'");
			
			echo "<script>self.location = '$SiteUrl/login/success/'</script>";
		}
		else
			echo "<script>self.location = '$SiteUrl/newpass/$PassText/notmatch/'</script>";
	}
	else
		echo "<script>self.location = '$SiteUrl/newpass/$PassText/empty/'</script>";
}

if(!empty($msg))
{
	switch($msg)
	{
		case "notmatch": $type = "error"; $msg = "New password and confirm password do not match."; break;
		case "empty": $type = "error"; $msg = "Password is empty."; break;
	}
}

if($error == 0)
{
	$content .= 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <div class="box-center">
					<div class="col large-5">
						<div class="row">
							<h3 class="mb-30"><span>$TITLE</span></h3>
							".displayMsg($type, $msg)."
							<form name="theForm" action="$SiteUrl/newpass/" method="post">
								<label>Email Address</label>
								<p style="margin-bottom:1em">$MemberLogin</p>
								<label>$TEXT_NEWPASSWORD_ACCOUNT</label>
								<input type="password" name="PassNew" required />
								<label>$TEXT_CONFIRMNEWPASSWORD_ACCOUNT</label>
								<input type="password" name="PassNewConfirm" required />
								<input type="submit" name="BtnNew" value="Create" class="button primary lowercase" />
							</form>
						</div>
					</div>
				</div>
</body>
</html>