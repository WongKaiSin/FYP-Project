<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");

$adName = $_SESSION["adName"];

if (isset($_GET["del"]) && isset($_GET["catID"])) {
    // Retrieve catID from request parameters
    $catID = $_GET['catID']; 

    // Check if there are products associated with this category
    $productResult = $db_conn->query("SELECT COUNT(*) as total FROM product_cat WHERE Active = 1 AND CatID = '$catID'");
    $productRow = $productResult->fetch_assoc();
    $totalProducts = $productRow['total'];

    if ($totalProducts > 0) {
        // If there are products, prevent deletion
        echo "<script>alert('Cannot delete category. There are products associated with it.');</script>";
        
    } else {
        // If there are no products, proceed with deletion
        $category_delete = "UPDATE category SET isUp = 0 WHERE catID = '$catID'";
        if ($db_conn->query($category_delete) === TRUE) {
            
            $cata_delete = "UPDATE category_cata SET isUp = 0 WHERE catID = '$catID'";
            
            if ($db_conn->query($cata_delete) === TRUE) {
                header("Location: product-view.php");
                exit();
            } else {
                echo "Error deleting product: " . $db_conn->error;
            }
        } else {
            // Handle errors, if any
            echo "Error deleting category: " . $db_conn->error;
        }
    }
}

if (isset($_GET["del"]) && isset($_GET["cataID"])) {
    // Retrieve cataID from request parameters
    $cataID = $_GET['cataID'];

    // Check if there are categories associated with this catalogue
    $categoryResult = $db_conn->query("SELECT COUNT(*) as total FROM category_cata WHERE isUp = 1 AND cataID = '$cataID'");
    $categoryRow = $categoryResult->fetch_assoc();
    $totalCategories = $categoryRow['total'];

    if ($totalCategories > 0) {
        // If there are categories, prevent deletion
        echo "<script>alert('Cannot delete catalogue. There are categories associated with it.');</script>";
    } else {
        // If there are no categories, proceed with deletion
        $cata_delete = "UPDATE catalogue SET isUp = 0 WHERE cataID = '$cataID'";
        if ($db_conn->query($cata_delete) === TRUE) {
            // Redirect back to the product list page
            header("Location: product-view.php");
            exit();
        } else {
            // Handle errors, if any
            echo "Error deleting catalogue: " . $db_conn->error;
        }
    }
}



?>

<script type="text/javascript">
    // Function to filter products based on selected category
function filterProducts() {
    var selectedCategory = ''; // Variable to store selected category ID
    var radios = document.querySelectorAll('.category-filter:checked');
    
    // Get selected category ID
    if (radios.length > 0) {
        selectedCategory = radios[0].getAttribute('data-catid');
    }

    // Show/hide products based on selected category
    var products = document.querySelectorAll('.product-item');
    products.forEach(function(product) {
        var categories = product.getAttribute('data-categories').split(','); // Get categories of the product
        var shouldShow = selectedCategory === '' || categories.includes(selectedCategory);
        product.style.display = shouldShow ? 'block' : 'none'; // Show or hide the product
    });
}

// Attach event listener to category radio buttons
document.addEventListener('DOMContentLoaded', function() {
    var radios = document.querySelectorAll('.category-filter');
    radios.forEach(function(radio) {
        radio.addEventListener('change', filterProducts);
    });
});

// Function to reset filter and show all products
function resetFilter() {
    // Clear selection of radio buttons
    var radios = document.querySelectorAll('.category-filter');
    radios.forEach(function(radio) {
        radio.checked = false;
    });

    // Show all products
    var products = document.querySelectorAll('.product-item');
    products.forEach(function(product) {
        product.style.display = 'block';
    });
}

// Attach event listener to reset filter button
document.addEventListener('DOMContentLoaded', function() {
    var resetFilterBtn = document.getElementById('resetFilterBtn');
    resetFilterBtn.addEventListener('click', function() {
        resetFilter();
    });
});

</script>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Product List</title>
    <style>
        .product-list-container {
            width: 70%;
            float: left;
        }

        /* CSS for filter sidebar */
        .filter-sidebar {
            width: 30%;
            float: right;
            padding-left: 25px;
            padding-top: 150px;
            /*position: fixed; /* Fix the sidebar position */
            right: 30px; /* Align the sidebar to the right */
            /*overflow: auto;
            height: 60%;*/
           
        }
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        .text-red {
            color: red;
        }
        .text-black {
            color: black;
        }
        .button-group{
            padding-top: 20px;
        }
    </style>
</head>
<script type="text/javascript">
    function confirmation() {
        return confirm("Do you want to delete?");
    }
</script>
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
                <div class="clearfix">
                    <div class="product-list-container">
                        <?php
                            // Fetch and display category names
                            $categoryResult = $db_conn->query("SELECT * FROM category WHERE isUp=1");
                            while($categoryRow = $categoryResult->fetch_assoc()) {

                                $catID = $categoryRow["catID"];
                                
                                //add delete edit button
                                echo '<h3>' . $categoryRow["catName"] . 
                                '<a href="product-add.php?catID='.$catID .'"   style="padding-left: 10px;"><i class="mdi mdi-plus-box-outline"></i></a>
                                <a href="product-category-edit.php?catID='.$catID.'"   style="padding: 0px;"><i class="mdi mdi-table-edit"></i></a>
                                <a href="product-view.php?del=1&catID='.$catID.'" onclick="return confirmation();"" style="padding: 0px;"><i class="mdi mdi-delete-empty"></i></a></h3>';
                                
                                
                                
                                // Fetch and display products for each category
                                $productResult = $db_conn->query("SELECT * FROM product_cat pc ,product p WHERE p.ProID=PC.ProID AND p.isUp=1 AND CatID = " . $catID );
                                echo '<div class="row">';
                                while($productRow = $productResult->fetch_assoc()) {
                                    $ProID = $productRow["ProID"];
                                    $stock = $productRow['ProStock'];
                                    $stockClass = $stock < 10 ? 'text-red' : 'text-black';
                        ?>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 product-item" data-categories="<?php echo $catID ?>">
                                <!-- .card -->
                                <div class="card card-figure">
                                    <!-- .card-figure -->
                                    <figure class="figure">
                                        <!-- .figure-img -->
                                        <div class="figure-img">
                                            <!-- Replace the src attribute with the actual image source -->
                                            <?php
                                                    $img_sql = $db_conn->query("SELECT * FROM product_image WHERE `ProID` = $ProID AND `ImageName`=1");
                                                    while ($img_row = $img_sql->fetch_assoc()) {
                                                        $ImageName = $img_row['ImageName'];
                                                        $ImageExt = $img_row['ImageExt'];
                                                        $image_url = $ImageName . "." . $ImageExt;
                                                    }
                                                    ?>
                                            <img class="img-fluid" style="height:180px; width: 1000px;" src="../upload/product/<?php echo $ProID; ?>/<?php echo $image_url; ?>" alt="Card image cap">
                                            <div class="figure-action">
                                            <a href="product-desc.php?ProID=<?php echo $ProID; ?>" class="btn btn-block btn-sm btn-primary">Description</a>
                                            </div>
                                        </div>
                                        <!-- /.figure-img -->
                                        <!-- .figure-caption -->
                                        <figcaption class="figure-caption">
                                            <p class="text-muted mb-0"><?php echo $productRow["ProName"]; ?></p>
                                            <p class="<?php echo $stockClass; ?>"><small><strong>[ STOCK : <?php echo $stock; ?> ]</small></strong></p>
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
                    </div>
                    
                    <div class="filter-sidebar">
    <div class="product-sidebar">
        <div class="product-sidebar-widget">
            <h2 class="product-sidebar-widget-title">Catalogue</h2>
            <?php 
            // Fetch and display unique category names with associated catalogues
            $cataResult = $db_conn->query("SELECT * FROM catalogue WHERE isUp=1");
            while ($cataRow = $cataResult->fetch_assoc()) {
                $cataID = $cataRow["cataID"];
            ?>
                <h5><?php echo $cataRow["cataName"]; ?>
                    <a href="product-category-add.php?cataID=<?php echo $cataID; ?>" style="padding-left: 10px;"><i class="far fa-plus-square"></i></a>
                    <a href="product-catalogue-edit.php?cataID=<?php echo $cataID; ?>" style="padding: 0px;"><i class="far fa-edit"></i></a>
                    <a href="product-view.php?del=1&cataID=<?php echo $cataID; ?>" onclick="return confirmation();" style="padding: 0px;"><i class="far fa-trash-alt"></i></a>
                </h5>
                <?php 
                // Fetch and display categories associated with the current catalogue
                $categoryResult = $db_conn->query("SELECT * FROM category_cata WHERE isUp=1 AND cataID='$cataID'");
                while ($categoryRow = $categoryResult->fetch_assoc()) {?>
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input category-filter" name="category" id="cat-<?php echo $categoryRow["catID"]; ?>" data-catid="<?php echo $categoryRow["catID"]; ?>">
                        <label class="custom-control-label" for="cat-<?php echo $categoryRow["catID"]; ?>"><?php echo $categoryRow["catName"]; ?></label>
                    </div>
                <br/>
            <?php 

            }
            } ?>
            <div class="button-group">
                <a href="product-catalogue-add.php" class="btn btn-outline-light">Add Catalogue</a>
                <button class="btn btn-outline-light" id="resetFilterBtn">Clear All</button>
            </div>
        </div>
    </div>
</div>

                </div>
            </div>
        </div>
    </div>
</body>

</html>
