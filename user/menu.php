<?php
include("lib/db.php");
$SiteUrl = "http://localhost:80/FYP-Project";

if(isset($_GET['CatID'])) {
    $category = $_GET['CatID'];

    $pro_query = $db_conn->query("SELECT product_cat.ProName, product.ProPrice, product.ProID 
                                  FROM product_cat 
                                  JOIN product ON product_cat.ProID = product.ProID 
                                  WHERE product_cat.CatID = '$category'");
} else {

    $pro_query = $db_conn->query("SELECT product_cat.ProName, product.ProPrice, product.ProID 
                                  FROM product_cat 
                                  JOIN product ON product_cat.ProID = product.ProID");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("lib/head.php"); ?>
  <title>Menu | London Bagel Museum</title>

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
          <h2>Menu</h2>
          <ol>
            <li><a href="index.php">Home</a></li>
            <li>Menu</li>
          </ol>
        </div>

      </div>
    </div><!-- End Breadcrumbs -->


    <section class="sample-page">
      <div class="section-header">
        <h2>London Bagel Museum Menu</h2>
        <p>Check Our <span>Menu</span></p>
      </div>
      <div class="container" data-aos="fade-up">
        <div class="row"> <!-- Wrap the sidebar and product display in a row -->
          <!-- Sidebar -->
          <div class="col-lg-3">
            <!-- Sidebar content -->
            <aside id="product_list_food" class="product_list">
              <ul class='menu'>
                <li class="dropdown">
                  <a href="?">All Products</a>
                </li>
              </ul>
            </aside>

            <aside id="product_list_food" class="product_list">
              <ul class='menu'>
                <li class="dropdown">
                  <a href="#">Food<i class="bi bi-chevron-down dropdown-indicator"></i></a>
                  <ul class="dropdown-menu">
                    <li><a href="?CatID=2">Bagel</a></li>
                    <li><a href="?CatID=3">Sandwich</a></li>
                    <li><a href="?CatID=3">Cream Cheese</a></li>
                  </ul>
                </li>
              </ul>
            </aside>

            <aside id="product_list_soup" class="product_list">
              <ul class='menu'>
                <li class="dropdown">
                  <a href="#">Homemade Soup<i class="bi bi-chevron-down dropdown-indicator"></i></a>
                  <ul class="dropdown-menu">
                    <li><a href="?CatID=4">Soup</a></li>
                  </ul>
                </li>
              </ul>
            </aside>

            <aside id="product_list_drinks" class="product_list">
              <ul class='menu'>
                <li class="dropdown">
                  <a href="#">Drinks<i class="bi bi-chevron-down dropdown-indicator"></i></a>
                  <ul class="dropdown-menu">
                    <li><a href="?CatID=1">Coffee</a></li>
                    <li><a href="?CatID=">Non-Coffee</a></li>
                    <li><a href="?CatID=">Tea</a></li>
                  </ul>
                </li>
              </ul>
            </aside>
          </div>

          <!-- Product Display -->
          <div class="col-lg-9">
            <div class="product-display">
              <?php
              if ($pro_query->num_rows > 0) {
                // Output data of each row
                while ($row = $pro_query->fetch_assoc()) {
                  $ProID = $row['ProID']; // Fetching Product ID
                  $img_sql = "SELECT `ImageName`, `ImageExt` FROM product_image WHERE `ProID` = $ProID";
                  $img_query = $db_conn->query($img_sql);
                  
                  echo '<div class="product-item">';
                  if ($img_query->num_rows > 0) {
                    $img_row = $img_query->fetch_assoc();
                    $ImageName = $img_row['ImageName'];
                    $ImageExt = $img_row['ImageExt'];
                    $image_url = $SiteUrl . "/upload/product/" . $ImageName . "." . $ImageExt;
                    echo "<img src='$image_url' style='width:100%'>";
                  }
                  echo "<p>" . $row["ProName"] . "</p>";
                  echo "<p><span><b>RM " . $row["ProPrice"] . "</b></span></p>"; 
                  echo '</div>';
                }
              } else {
                echo "0 results";
              }
              ?>
            </div>
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
