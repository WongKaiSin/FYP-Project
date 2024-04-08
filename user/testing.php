<?php
include("lib/db.php");

// Fetch distinct categories
$categoryQuery = "SELECT DISTINCT category FROM product";
$categoryResult = mysqli_query($conn, $categoryQuery);

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
      <div class="container" data-aos="fade-up">

        <!-- Sidebar -->
        <div class="col-lg-3">
          <?php 
            // Iterate over each category
            while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
              $category = $categoryRow['category'];
              echo "<aside id='product_list_$category' class='product_list'>";
              echo "<ul class='menu'>";
              echo "<li class='dropdown'>";
              echo "<a href='#'>$category<i class='bi bi-chevron-down dropdown-indicator'></i></a>";
              echo "<ul class='dropdown-menu'>";
              
              // Fetch products for the current category
              $productQuery = "SELECT * FROM product WHERE category = '$category'";
              $productResult = mysqli_query($conn, $productQuery);
              
              // Display products for the current category
              if (mysqli_num_rows($productResult) > 0) {
                while ($productRow = mysqli_fetch_assoc($productResult)) {
                  echo "<li><a href='#'>" . $productRow['product_name'] . "</a></li>";
                }
              } else {
                echo "<li>No products found in the '$category' category.</li>";
              }

              echo "</ul>";
              echo "</li>";
              echo "</ul>";
              echo "</aside>";
            }
          ?>
        </div>
        <!-- Main Content -->

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

<?php
// Close the database connection
mysqli_close($conn);
?>