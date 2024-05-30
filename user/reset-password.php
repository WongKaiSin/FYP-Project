<?php
include("lib/db.php");
include("lib/function.php");

$function = new Functions;

$TITLE = "Create New Password";
$SiteUrl = "http://localhost:80/FYP-Project/user";

$token = isset($_GET["token"]) ? $_GET["token"] : '';
$content = "";
$error = 0;

// Split the token by '#' to separate the member ID and the timestamp
$tokenParts = explode("#", $token);

// Extract the member ID from the first part of the token
$MemberID = substr($tokenParts[0], 4); // Assuming the member ID is encoded starting from the 5th character

// Execute SELECT query to fetch member data
$check_query = "SELECT MemberLogin, MemberEmail, MemberReset FROM member WHERE MemberID=$MemberID";
$result = $db_conn->query($check_query);
$check_num = $result->num_rows;

if ($check_num == 0) {
    $error = 1;
    echo '<script>alert("Invalid website.")</script>';
} else {
    $check_row = $result->fetch_assoc();

    $MemberEmail = $check_row["MemberEmail"];
    $MemberReset = $check_row["MemberReset"];

    $expiredTime = date("Y-m-d H:i:s", strtotime("+20 minutes", strtotime($MemberReset)));

    if ($expiredTime < date("Y-m-d H:i:s")) {
        $error = 1;
        echo '<script>alert("The time is expired.")</script>';
        exit();
    }
}

if (isset($_POST['BtnNew'])) {
    // receive all input values from the form
    $MemberPass = $_POST['MemberPass'];  
    $PassNewConfirm = $_POST['PassNewConfirm']; 

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if ($MemberPass !== $PassNewConfirm) {
        echo '<script>alert("New password and confirm password do not match.")</script>';
    } else {
        // Hash the password using PassSign function
        $MemberPassHashed = $function->PassSign($MemberEmail, $MemberPass);  

        // Update user's profile in the database
        $sql = "UPDATE member SET MemberPass = '$MemberPassHashed' WHERE MemberID = $MemberID AND isUp='1'";
        $query = $db_conn->query($sql);

        if ($query) {
            // Get the current timestamp
            $MemberReset = date('Y-m-d H:i:s');

            // Update the MemberReset in the database
            $updateSql = "UPDATE member SET MemberReset = '$MemberReset' WHERE MemberID = $MemberID";
            $updateQuery = $db_conn->query($updateSql);

            if ($updateQuery) {
                echo '<script>alert("Reset the password successfully!")</script>';
                header('Location: registration.php');
                exit(); // Exit after redirection
            } else {
                echo '<script>alert("Error updating reset time")</script>';
            }
        } else {
            echo '<script>alert("Failed to reset the password.")</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("lib/head.php"); ?>
    <title><?php echo $SEO_TITLE; ?></title>
</head>

<script>
  function togglePasswordVisibility(inputId) {
    var x = document.getElementById(inputId);
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }
</script>

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
			<h2>Create New Password</h2>
			<ol>
				<li><a href="index.php">Home</a></li>
				<li>Create New Password</li>
			</ol>
			</div>

		</div>
		</div><!-- End Breadcrumbs -->

        <section class="reset" id="reset">
            <div class="container" data-aos="fade-up">
			<h3><span>Create Your New Password</span></h3>

                <form class='theForm' action='<?php echo "$SiteUrl/reset-password.php?token=$token"; ?>' method='post'>
                    <label><b>Email Address</b></label>
                    <p class="mb-20"><?php echo $MemberEmail; ?></p>
					
					<label for="MemberPass"><b>New Password</b></label>
                    <div class="password-input">
                    <input type="password" name="MemberPass" class="form-control" id="MemberPass" placeholder="Please Enter Your Password" required>
                    <span class="toggle-password" onclick="togglePasswordVisibility('MemberPass')">Show Password</span>
                    </div>

					<label for="PassNewConfirm"><b>Confirm New Password</b></label>
                    <div class="password-input">
                    <input type="password" name="PassNewConfirm" class="form-control" id="PassNewConfirm" placeholder="Please Enter the Confirmation Password" required>
                    <span class="toggle-password" onclick="togglePasswordVisibility('PassNewConfirm')">Show Password</span>
                    </div>

                    <input type='submit' name='BtnNew' value='Update' class='button primary lowercase' />
                </form>

            </div>
        </section>

    </main><!-- End #main -->
    <?php include("lib/footer.php"); ?>

    <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <div id="preloader"></div>

</body>

</html>
