<?php
require_once("lib/db.php");
if(isset($_GET["ProUrl"]))
  $ProUrl = $_GET["ProUrl"];

if($ProUrl>0)
{
  // Prepare the SQL statement with prepared statements to prevent SQL injection
  $stmt = $db_conn->prepare("SELECT * FROM product WHERE ProUrl = ?");
  
  // Bind the parameter
  $stmt->bind_param("s", $ProUrl);
  
  // Execute the statement
  $stmt->execute();
  
  // Store the result
  $pro_query = $stmt->get_result();

  if($pro_query->num_rows > 0)
  {
    $row = $pro_query->fetch_assoc();

    $ProID = $row["ProID"];
    $ProName = $row["ProName"];
    $ProPrice = $row["ProPrice"];
    $ProDesc = $row["ProDesc"];
    $Ingre = $row["Ingredient"];
    $store = $row["Storage"];
    $life = $row["ShelfLife"];
    
     // Prepare the SQL statement for category query
    $stmt_cat = $db_conn->prepare("SELECT CatName FROM product_cat WHERE ProID = ?");
    $stmt_cat->bind_param("i", $ProID);
    $stmt_cat->execute();
    $cat_query = $stmt_cat->get_result();

    $cat_row = $cat_query->fetch_assoc();
    $CatName = $cat_row["CatName"];

    // Prepare the SQL statement for category query
    $stmt_img = $db_conn->prepare("SELECT `ImageName`, `ImageExt` FROM product_image WHERE ProID = ?");
    $stmt_img->bind_param("i", $ProID);
    $stmt_img->execute();
    $img_query = $stmt_img->get_result();

    $img_row = $img_query->fetch_assoc();
    $ImgName = $img_row["ImageName"];
    $ImgExt = $img_row["ImageExt"];
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
          <h2><?php echo $ProName?></h2>
          <ol>
            <li><a href="index.php">Home</a></li>
            <li><a href="menu.php?cat=<?php echo urlencode($CatName);?>"> <?php echo $CatName?></a></li>
          </ol>
        </div>
      </div>
    </div><!-- End Breadcrumbs -->

    <section class="sample-page">
      <div class="container" data-aos="fade-up">
        <div class="row mt-30">
          <div class="col product-gallery large-6">
            <div class="row">
              <div class="col medium-12 small-12 product-images">
                
              </div>
            </div>
            <p>
              You can duplicate this sample page and create any number of inner pages you like!
            </p>
          </div>
        </div>
      </div>
    </section>

  </main><!-- End #main -->
  <?php include("lib/footer.php"); ?>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

</body>
</html>