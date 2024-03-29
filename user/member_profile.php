<?php
session_start();

// Initialize variables to avoid "Undefined variable" errors
$MemberEmail = ""; // Initialize $MemberEmail
$MemberName = ""; // Initialize $MemberName
$MemberPhone = ""; // Initialize $MemberPhone

// Check if user is logged in and retrieve their profile information
if (isset($_SESSION['MemberEmail'])) {
    // Assuming you have a method to fetch user profile information from the database
    // Replace the placeholders with actual code to retrieve the user's information
    $MemberEmail = $_SESSION['MemberEmail']; // Example: $MemberEmail = getUserEmail($_SESSION['MemberEmail']);
    $MemberName = ""; // Example: $MemberName = getUserName($_SESSION['MemberEmail']);
    $MemberPhone = ""; // Example: $MemberPhone = getUserPhone($_SESSION['MemberEmail']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("lib/head.php"); ?>
  <title>Profile | London Bagel Museum</title>
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
                <h3><span>Profile</span></h3>
                
                <form name="Form1" method="post" action="forms/update_profile.php" enctype="multipart/form-data">
                    <label>Email Address</label>
                    <p class="mb-20"><?php echo $MemberEmail; ?></p>
                    
                    <label>Name</label>
                    <input type="text" name="MemberName" value="<?php echo $MemberName; ?>" required>
                    
                    <label>Contact No.</label>
                    <input type="text" name="MemberContact" value="<?php echo $MemberPhone; ?>" required>

                    <div class="text-center">
                        <input type="submit" name="BtnUpdateProfile" value="Update" class="button primary lowercase mt-20">
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

</body>
</html>