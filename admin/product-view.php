<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");

$adUser = $_SESSION['adUser'];

if (isset($_GET["del"])) {
    // Retrieve catID from request parameters
    $catID = $_GET['catID']; 

    // Check if there are products associated with this category
    $productResult = $db_conn->query("SELECT COUNT(*) as total FROM product_cat WHERE CatID = '$catID'");
    $productRow = $productResult->fetch_assoc();
    $totalProducts = $productRow['total'];

    if ($totalProducts > 0) {
        // If there are products, prevent deletion
        echo "<script>alert('Cannot delete category. There are products associated with it.');</script>";
        
    } else {
        // If there are no products, proceed with deletion
        $sql_delete = "DELETE FROM category WHERE catID = '$catID'";
        if ($db_conn->query($sql_delete) === TRUE) {
            // Redirect back to the product list page
            header("Location: product-view.php");
            exit();
        } else {
            // Handle errors, if any
            echo "Error deleting category: " . $db_conn->error;
        }
    }
}
    


?>
<!doctype html>
<html lang="en">
<script type="text/javascript">
    function confirmation() {
        return confirm("Do you want to delete this category?");
    }
</script>
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
                                        <li class="breadcrumb-item active" aria-current="page">View Product List</li>
                                    </ol>
                                </nav>
                           </div>
                        </div>
                    </div>
                </div>
                <?php
                    // Fetch and display category names
                    mysqli_select_db($db_conn,"bagel");
                    $categoryResult = $db_conn->query("SELECT * FROM category");
                    while($categoryRow = $categoryResult->fetch_assoc()) {

                        $catID = $categoryRow["catID"];
                        
                        //delete category
                        echo '<h3>' . $categoryRow["catName"] . 
                        '<a href="product-category-edit.php?catID='.$catID.'"   style="color: #1e90ff; padding-left: 10px;"><i class="m-r-10 mdi mdi-lead-pencil"></i></a>|
                        <a href="product-view.php?del=1&catID='.$catID.'" onclick="return confirmation();"" style="color: red;padding-left: 0px;"><i class="m-r-10 mdi mdi-delete-forever"></i></a></h3>';
                        // Add Product button
                        echo '<a href="product-add.php?catID='.$catID .'"class="btn btn-success">Add Product</a>';
                        
                        // Fetch and display products for each category
                        mysqli_select_db($db_conn,"bagel");
                        $productResult = $db_conn->query("SELECT * FROM product_cat WHERE CatID = " . $catID);
                        echo '<div class="row">';
                        while($productRow = $productResult->fetch_assoc()) {
                            $ProID = $productRow["ProID"];
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
                                    <a href="product-desc.php?ProID=<?php echo $ProID; ?>" class="btn btn-block btn-sm btn-primary">Description</a>
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
               </br></br></br> <a href="product-category-add.php" class="btn btn-secondary  add-category-btn">Add Category</a></br></br>
            </div>
        </div>
    </div>
</body>

</html>