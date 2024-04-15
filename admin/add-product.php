<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");

// Check if a category ID is provided in the URL
if(isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
} else {
    // Redirect to the product view page if no category ID is provided
    header("Location: product-view.php");
    exit();
}

?>
<!doctype html>
<html lang="en">

<head>
    <title>Add Product</title>
</head>

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
                            <h2 class="pageheader-title">Add Product</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="product-view.php" class="breadcrumb-link">View Product List</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Add Product</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <!-- Add your form for adding a new product here -->
                        <form action="add-product-process.php" method="post">
                            <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                            <div class="form-group">
                                <label for="product_name">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" required>
                                <label for="productPicture">Product Picture</label>
                                <input type="file" class="form-control-file" id="productPicture" name="#">
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Add Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
