<?php
include("lib/db.php");
include("lib/function.php");

$function = new Functions;

$TITLE = "Forgot Your Password?";
$SiteUrl = "http://localhost:80/FYP-Project/user";

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

        $msg[] = "An email have sent to your email address.";
	}
	
	if(!empty($msg))
		$type = "error";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("lib/head.php"); ?>
  <title>Forgot Password | London Bagel Museum</title>

  <style>
    .button {
      background: var(--color-primary);
      border: 0;
      padding: 8px 30px; /* Adjust padding to make the button smaller */
      color: #fff;
      transition: 0.4s;
      border-radius: 50px;
      font-size: 12px; /* Adjust font size */
    }

    .button:hover {
      background: #ec2727;
    }
  </style>

</head>
<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">
      <?php 
        include("lib/logo.php");
        include("lib/topmenu.php");
      ?>
    </div>
  </header><!-- End Header -->

  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Forgot Password</h2>
          <ol>
            <li><a href="index.php">Home</a></li>
            <li>Forgot Password</li>
          </ol>
        </div>

      </div>
    </div><!-- End Breadcrumbs -->

    <section class="forgot" id="forgot">
      <div class="container" data-aos="fade-up">

      <h3 class="mb-30"><span>Forgot Password</span></h3>
                <p>Please enter your email address used for login. You will receive a link to create a new password via email.</p>
                <form name="theForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <label><b>Email Address</b></label>
                    <input type="email" name="PassEmail" value="<?php echo htmlspecialchars($PassEmail); ?>" required />
                    <input type="submit" name="BtnPass" value="Reset Password" class="button primary lowercase" />
                </form>
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

      </div>
    </section>

  </main><!-- End #main -->
  <?php include("lib/footer.php"); ?>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

</body>
</html>

