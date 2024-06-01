<?php
include("lib/db.php");
$SiteUrl = "http://localhost:80/FYP-Project";

// Define the displayStars() function
function displayStars($rating) {
    $output = '';
    if ($rating > 0) {
        // Calculate stars only if the rating is available
        $fullStars = intval($rating); // Full stars
        $halfStar = $rating - $fullStars; // Half star

        // Full stars
        for ($i = 0; $i < $fullStars; $i++) {
            $output .= '<span class="fa fa-star checked"></span>';
        }

        // Half star
        if ($halfStar >= 0.5) {
            $output .= '<span class="fa fa-star-half-o checked"></span>';
        }

        // Empty stars
        $emptyStars = 5 - ceil($rating);
        if ($emptyStars > 0) {
            for ($i = 0; $i < $emptyStars; $i++) {
                $output .= '<span class="fa fa-star"></span>';
            }
        }
    } else {
        // Display no rating if the average rating is not available
        $output .= '<span>No rating available</span>';
    }

    return $output;
}


if (isset($_GET['CatID'])) {
  $category_name = $_GET['CatID'];
  $category_query = $db_conn->query("SELECT CatID FROM category_cata WHERE catName = '$category_name'");
  if ($category_query->num_rows > 0) {
      $category_row = $category_query->fetch_assoc();
      $category_id = $category_row['CatID'];
      $pro_query = $db_conn->query("SELECT pc.ProName, p.ProPrice, p.ProID, p.ProUrl
                                  FROM product_cat pc
                                  JOIN product p ON pc.ProID = p.ProID 
                                  WHERE pc.CatID = '$category_id' AND p.isUp = 1");
  } else {
      $pro_query = $db_conn->query("SELECT pc.ProName, p.ProPrice, p.ProID , p.ProUrl
                                FROM product_cat pc
                                JOIN product p ON pc.ProID = p.ProID 
                                WHERE p.isUp = 1");
  }
} else {
  $pro_query = $db_conn->query("SELECT pc.ProName, p.ProPrice, p.ProID , p.ProUrl
                              FROM product_cat pc
                              JOIN product p ON pc.ProID = p.ProID 
                              WHERE p.isUp = 1");
}
?>

<script>
function search() {
    // Declare variables
    var input, filter, productDisplay, productItems, productName, i, txtValue;
    input = document.getElementById('myInput');
    filter = input.value.toUpperCase();
    productDisplay = document.getElementById("product-display");
    productItems = productDisplay.getElementsByClassName("product-item");

    // Loop through all product items, and hide those who don't match the search query
    for (i = 0; i < productItems.length; i++) {
        productName = productItems[i].getElementsByTagName("p")[0]; // Assuming the first <p> tag contains the product name
        if (productName) {
            txtValue = productName.textContent || productName.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                productItems[i].style.display = "";
            } else {
                productItems[i].style.display = "none";
            }
        }
    }
}
</script>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("lib/head.php"); ?>
  <title>Menu | London Bagel Museum</title>
  <style>
     .search-container {
        position: relative;
        display: flex;
        justify-content: end;
        margin-bottom: 20px;
    }
    .search-input {
        width: 50%;
        padding: 10px 15px;
        border-radius: 25px;
        border: 2px solid #ccc;
        transition: all 0.3s ease;
    }
    .search-input:focus {
        border-color: #ec2727;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.5);
        outline: none;
    }
    .search-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 1.2em;
        transition: color 0.3s ease;
    }
    .search-input:focus + .search-icon {
        color: #000000;
        
    }

    .star-rating-container {
        display: flex;
        justify-content: center; /* Center horizontally */
        align-items: center; /* Center vertically */
        width: 100%;
    }

    .star-rating-container .checked {
        color: orange;
    }

    .star-rating-container span {
        color: #c5c5c5;
        font-size: 16px;
    }

  </style>
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
            <aside class="product_list">
              <ul class='menu'>
                <li class="dropdown">
                  <a href="?">All Products</a>
                </li>
              </ul>
            </aside>

            <?php
            // Fetch and display catalogue names
            $catalogue_query = $db_conn->query("SELECT cc.CatCataID, cc.cataName, c.catName 
                                    FROM category_cata cc 
                                    JOIN category_cata c ON cc.CatCataID = c.CatCataID");
            if ($catalogue_query->num_rows > 0) {
                $catalogues = [];
                while ($catalogue_row = $catalogue_query->fetch_assoc()) {
                    $catalogues[$catalogue_row['cataName']][] = $catalogue_row;
                }

                foreach ($catalogues as $cataName => $categories) {
                    echo '<aside class="product_list">';
                    echo '<ul class="menu">';
                    echo '<li class="dropdown">';
                    echo '<a href="?CatCataID='.$cataName.'">'.$cataName.'<i class="bi bi-chevron-down dropdown-indicator"></i></a>';
                    echo '<ul class="dropdown-menu">';
                    foreach ($categories as $category) {
                        $catID = $category['CatID'];
                        $catName = $category['catName'];
                        echo '<li><a href="?CatID='.$catID.'">'.$catName.'</a></li>';
                    }
                    echo '</ul>'; // Close dropdown-menu
                    echo '</li>'; // Close dropdown
                    echo '</ul>'; // Close menu
                    echo '</aside>';
                }
            }
            ?>
        </div>

          <!-- Product Display -->
          <div class="col-lg-9">
            <div class="search-container">
              <input type="text" class="form-control search-input" id="myInput" onkeyup="search()" placeholder="Search..">
              <i class="bi bi-search search-icon"></i>
            </div>
            <div id="product-display" class="product-display">
            <?php
              if ($pro_query->num_rows > 0) {
                  // Output data of each row
                  while ($row = $pro_query->fetch_assoc()) {
                      // Fetching Product ID
                      $ProID = $row['ProID'];

                      // Fetching Product URL
                      $ProUrl = $row['ProUrl'];

                      // Fetching Product Name
                      $ProName = $row['ProName'];

                      // Fetching the first image associated with the product
                      $img_sql = $db_conn->query("SELECT * FROM product_image WHERE `ProID` = $ProID AND `ImageName`=1");
                      $image_url = ''; // Initialize image URL variable
                      while ($img_row = $img_sql->fetch_assoc()) {
                          $ImageName = $img_row['ImageName'];
                          $ImageExt = $img_row['ImageExt'];
                          $image_url = $ImageName . "." . $ImageExt;
                      }

                      echo '<div class="product-item mb-3">
                          <a href="'.$SiteUrl.'/user/menu-info.php?ProUrl='.$ProUrl.'">';

                      if (!empty($image_url)) {
                          // Show the fetched image
                          echo "<img class='img-fluid' style='height:180px; width: 1000px;' src='../upload/product/$ProID/$image_url' alt='Card image cap'>";
                      } else {
                          // Provide a default image path if no image found
                          echo "<img class='img-fluid' style='height: 180px; width: 1000px;' src='path_to_default_image' alt='Default Image'>";
                      }

                      echo "<p class='mt-2'>$ProName</p>";
                      echo "<p><span><b>RM " . $row["ProPrice"] . "</b></span></p>";

                      // Fetch and display average rating for this product
                      $avg_rating_query = "SELECT AVG(RevRate) AS avg_rate FROM review_rate WHERE ProID = $ProID";
                      $avg_rating_result = $db_conn->query($avg_rating_query);
                      if ($avg_rating_result && $avg_rating_row = $avg_rating_result->fetch_assoc()) {
                          echo '<div class="star-rating-container">';
                          echo displayStars($avg_rating_row['avg_rate']);
                          echo '</div>';
                      } else {
                          echo '<span>No rating available</span>';
                      }

                      echo '</a></div>';
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

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>
