<?php
require_once("lib/db.php");
if(isset($_GET["ProUrl"]))
  $ProUrl = $_GET["ProUrl"];

if($ProUrl>0)
{
  $pro_query = $db_conn->query("SELECT * FROM product WHERE `ProUrl`='$ProUrl'");
	$pro_num = mysqli_num_rows($pro_query);

  if($pro_num>0)
  {
    $row = $pro_query->fetch_assoc();

    $ProID = $row["ProID"];
    $ProName = $row["ProName"];
    $ProPrice = $row["ProPrice"];
    $ProDesc = $row["ProDesc"];
    $Ingre = $row["Ingredient"];
    $store = $row["Storage"];
    $life = $row["ShelfLife"];
    $cat_query = $db_conn->query("SELECT `CatName` FROM product_cat WHERE `ProID`='".$ProID."'")
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("lib/head.php"); ?>
  <title><?php echo $ProName  // food name?> - LBM</title>
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
          <h2>Sample Inner Page</h2>
          <ol>
            <li><a href="index.php">Home</a></li>
            <li>Sample Inner Page</li>
          </ol>
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