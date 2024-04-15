<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");

?>
<!doctype html>
<html lang="en">

<head>
    <title>View Product List</title>
</head>
<style>
        .add-category-btn {
            float: right;
            margin-bottom: 10px;
        }
    </style>
<body>
    <div class="dashboard-main-wrapper">
        <?php 
            include("lib/navbar.php");
            include("lib/sidebar.php");
        ?>
        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h2 class="pageheader-title">View Product List</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="product-view.php" class="breadcrumb-link">View Product List</a></li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    // Fetch and display category names
                    mysqli_select_db($db_conn,"bagel");
                    $categoryResult = mysqli_query($db_conn, "SELECT * FROM category");
                    while($categoryRow = mysqli_fetch_assoc($categoryResult)) {
                        echo '<h3>' . $categoryRow["catName"] . '</h3>';
                        // Add Product button
                        echo '<a href="add-product.php?category_id=' . $categoryRow["catID"] . '" class="btn btn-success">Add Product</a>';
                        // Fetch and display products for each category
                        mysqli_select_db($db_conn,"bagel");
                        $productResult = mysqli_query($db_conn, "SELECT * FROM product_cat WHERE CatID = " . $categoryRow["catID"]);
                        echo '<div class="row">';
                        while($productRow = mysqli_fetch_assoc($productResult)) {
                ?>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                        <!-- .card -->
                        <div class="card card-figure">
                            <!-- .card-figure -->
                            <figure class="figure">
                                <!-- .figure-img -->
                                <div class="figure-img">
                                    <!-- Replace the src attribute with the actual image source -->
                                    <img class="img-fluid" src="assets/images/card-img.jpg" alt="Card image cap">
                                    <div class="figure-action">
                                        <a href="#" class="btn btn-block btn-sm btn-primary">Description</a>
                                    </div>
                                </div>
                                <!-- /.figure-img -->
                                <!-- .figure-caption -->
                                <figcaption class="figure-caption">
                                    <p class="text-muted mb-0"><?php echo $productRow["ProName"]; ?></p>
                                </figcaption>
                                <!-- /.figure-caption -->
                            </figure>
                            <!-- /.card-figure -->
                        </div>
                        <!-- /.card -->
                    </div>
                    
                <?php
                        }
                        echo '</div>'; // Close the row after displaying all products in this category
                    }
                ?>
               </br></br></br> <a href="add-category.php" class="btn btn-secondary  add-category-btn">Add Category</a></br></br>
            </div>
        </div>
    </div>
</body>

</html>
