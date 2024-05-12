<?php
include("lib/head.php");

require_once("lib/db.php");
$msg = (isset($_GET['msg']) ? $_GET["msg"] : "");

$MemberID = $_SESSION["MemberID"];
$CurrCart = $_SESSION["Cart"];

$CartSql = "";
if($MemberID > 0)
	$CartSql = " OR MemberID='$MemberID'";
	
$cart_query = mysqli_query($db_conn, "SELECT * FROM cart WHERE (CartSession='".$CurrCart."'".$CartSql.") ORDER BY CartID DESC");
$cart_num = mysqli_num_rows($cart_query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Cart</title>

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
          <h2>Cart</h2>
        </div>
      </div>
    </div><!-- End Breadcrumbs -->

    <section class="sample-page">
      <div class="container" data-aos="fade-up">

        <p>
          You can duplicate this sample page and create any number of inner pages you like!
        </p>

      </div>
    </section>

  </main><!-- End #main -->
  <?php include("lib/footer.php"); ?>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

</body>
</html>