<?php
session_start();

// Initialize variables to avoid "Undefined variable" errors
$MemberEmail = ""; // Initialize $MemberEmail
$MemberPass = ""; // Initialize $MemberPass

// Check if user is logged in and retrieve their profile information
if (isset($_SESSION['MemberEmail'])) {
    // Assuming you have a method to fetch user profile information from the database
    // Replace the placeholders with actual code to retrieve the user's information
    $MemberEmail = $_SESSION['MemberEmail']; // Example: $MemberEmail = getUserEmail($_SESSION['MemberEmail']);
    $MemberPass = ""; // Example: $MemberPass = getUserPass($_SESSION['MemberEmail']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("lib/head.php"); ?>
  <title>Change Password | London Bagel Museum</title>
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
          <h2>Setting</h2>
          <ol>
            <li><a href="index.php">Home</a></li>
            <li>Setting</li>
          </ol>
        </div>
      </div>
    </div><!-- End Breadcrumbs -->

    <div class="container">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3">
          <?php include("lib/sidebar.php"); ?>
        </div>
        <!-- Main Content -->
        <div class="col-lg-9">
          <section id="profile" class="profile">
            <div class="container" data-aos="fade-up">
                <h3><span>Change Password</span></h3>
                
                <form class="UpPass" method="post" action="form_update_password.php" enctype="multipart/form-data">
                    <label><b>Email Address</b></label>
                    <p class="mb-20"><?php echo $MemberEmail; ?></p>

                    <label for="MemberPass"><b>Password</b></label>
                    <div class="password-input">
                    <input type="password" name="MemberPass" class="form-control" id="MemberPass" placeholder="Please Enter Your Password" required>
                    <span class="toggle-password" onclick="togglePasswordVisibility('MemberPass')">Show Password</span>
                    </div>

                    <label for="CfmPass"><b>Confirm Password</b></label>
                    <div class="password-input">
                    <input type="password" name="CfmPass" class="form-control" id="CfmPass" placeholder="Please Enter the Confirmation Password" required>
                    <span class="toggle-password" onclick="togglePasswordVisibility('CfmPass')">Show Password</span>
                    </div>

                    <div class="text-center">
                        <input type="submit" name="BtnUpdatePass" value="Update" class="button primary lowercase mt-20">
                    </div>
                </form>

            </div>
          </section>
        </div>
      </div>
    </div>

  </main><!-- End #main -->
  <?php include("lib/footer.php"); ?>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <?php
    if (isset($_SESSION['alert'])) {
        echo "<script>alert('{$_SESSION['alert']}');</script>";
        unset($_SESSION['alert']); // Remove the alert message from session after displaying it
    }
  ?>

</body>
</html>