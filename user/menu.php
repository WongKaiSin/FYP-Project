<?php 
include("lib/db.php");
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
            <aside id="product_list_food" class="product_list">
                <ul class='menu'>
                <li class="dropdown">
                        <a href="<?php echo $SiteUrl ?>">All Products</a>
                </li>
                </ul>
            </aside>

            <aside id="product_list_food" class="product_list">
                <ul class='menu'>
                <li class="dropdown">
                        <a href="#">Food<i class="bi bi-chevron-down dropdown-indicator"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Bagel</a></li>
                            <li><a href="#">Sandwich</a></li>
                        </ul>
                    </li>
                </ul>
            </aside>

            <aside id="product_list_soup" class="product_list">
                <ul class='menu'>
                <li class="dropdown">
                        <a href="#">Homemade Soup<i class="bi bi-chevron-down dropdown-indicator"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Soup</a></li>
                        </ul>
                    </li>
                </ul>
            </aside>

            <aside id="product_list_drinks" class="product_list">
                <ul class='menu'>
                <li class="dropdown">
                        <a href="#">Drinks<i class="bi bi-chevron-down dropdown-indicator"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="">Coffee</a></li>
                            <li><a href="">Non-Coffee</a></li>
                            <li><a href="">Tea</a></li>
                        </ul>
                    </li>
                </ul>
            </aside>
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



////////////////////////////////////////////////////////
<?php 
include("lib/db.php");

// Fetch products from the "bagel" category
$sql = "SELECT * FROM products WHERE category = 'bagel'";
$result = mysqli_query($conn, $sql);
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
            <aside id="product_list_food" class="product_list">
                <ul class='menu'>
                <li class="dropdown">
                        <a href="<?php echo $SiteUrl ?>">All Products</a>
                </li>
                </ul>
            </aside>

            <aside id="product_list_food" class="product_list">
                <ul class='menu'>
                <li class="dropdown">
                        <a href="#">Food<i class="bi bi-chevron-down dropdown-indicator"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Bagel</a></li>
                            <?php 
                                // Check if there are any products in the "bagel" category
                                if (mysqli_num_rows($result) > 0) {
                                    // Output products
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<li><a href="#">' . $row['product_name'] . '</a></li>';
                                    }
                                } else {
                                    echo "<li>No products found in the 'bagel' category.</li>";
                                }
                            ?>
                        </ul>
                    </li>
                </ul>
            </aside>

            <aside id="product_list_soup" class="product_list">
                <ul class='menu'>
                <li class="dropdown">
                        <a href="#">Homemade Soup<i class="bi bi-chevron-down dropdown-indicator"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Soup</a></li>
                        </ul>
                    </li>
                </ul>
            </aside>

            <aside id="product_list_drinks" class="product_list">
                <ul class='menu'>
                <li class="dropdown">
                        <a href="#">Drinks<i class="bi bi-chevron-down dropdown-indicator"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="">Coffee</a></li>
                            <li><a href="">Non-Coffee</a></li>
                            <li><a href="">Tea</a></li>
                        </ul>
                    </li>
                </ul>
            </aside>
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