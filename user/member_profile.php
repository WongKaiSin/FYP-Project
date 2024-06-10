<?php
session_start();
include("lib/db.php");

// Check if user is logged in
if (!isset($_SESSION['MemberID'])) {
    header("Location: registration.php");
    exit();
}

// Initialize variables
$MemberEmail = "";
$MemberName = "";
$MemberPhone = "";
$AddAddress = "";
$AddPostcode = "";
$AddCity = "";
$AddState = "Melaka";
$AddCountry = "Malaysia";

// Fetch user profile information from the database
$MemberID = $_SESSION['MemberID'];
$sql = "SELECT MemberEmail, MemberName, MemberPhone FROM member WHERE MemberID = $MemberID";
$result = $db_conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $MemberEmail = $row['MemberEmail'];
    $MemberName = $row['MemberName'];
    $MemberPhone = $row['MemberPhone'];
}

$sqlAddress = "SELECT AddAddress, AddPostcode, AddCity, AddState, AddCountry FROM member_address WHERE MemberID = $MemberID";
$queryAddress = $db_conn->query($sqlAddress);

if ($queryAddress->num_rows > 0) {
    $addressRow = $queryAddress->fetch_assoc();
    $AddAddress = $addressRow['AddAddress'];
    $AddPostcode = $addressRow['AddPostcode'];
    $AddCity = $addressRow['AddCity'];
    $AddState = $addressRow['AddState'];
    $AddCountry = $addressRow['AddCountry'];
}

// Close database connection
$db_conn->close();
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
                
                <form class="UpProfile" method="post" action="form_update_profile.php" enctype="multipart/form-data">
                <div class="form-group">
                        <label><b>Email Address</b></label>
                        <p class="mb-20"><?php echo $MemberEmail; ?></p>

                        <label for="MemberName"><b>Name</b></label>
                        <input type="text" name="MemberName" class="form-control" id="MemberName" placeholder="Please Enter Your Name" value="<?php echo $MemberName; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="MemberPhone"><b>Contact No.</b></label>
                        <input type="text" class="form-control" name="MemberPhone" id="MemberPhone" placeholder="Please Enter Your Phone Number" value="<?php echo $MemberPhone; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="AddAddress"><b>Address</b></label>
                        <input type="text" class="form-control" name="AddAddress" id="AddAddress" placeholder="Please Enter Your Address"value="<?php echo $AddAddress; ?>" required>
                    </div>
                    <div class="form-group">
                            <label for="AddCity"><b>City & Postcode</b></label>
                            <select id="Postcode" name="StateAndPostcode" class="form-control" required>
                            <option>Select City and Postcode</option>
                            </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label for="AddState"><b>State</b></label>
                            <input type="text" name="AddState" class="form-control" id="AddState" placeholder="Please Enter Your State"value="<?php echo $AddState; ?>" required>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="AddCountry"><b>Country</b></label>
                            <input type="text" name="AddCountry" class="form-control" id="AddCountry" placeholder="Please Enter Your Country"value="<?php echo $AddCountry; ?>" required>
                        </div>
                    </div>

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

  <?php
    if (isset($_SESSION['alert'])) {
        echo "<script>alert('{$_SESSION['alert']}');</script>";
        unset($_SESSION['alert']); 
    }
  ?>

  <script>
    loadLocation(<?php echo $AddPostcode;?>);
  </script>

</body>
</html>
