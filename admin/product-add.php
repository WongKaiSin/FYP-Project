<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");
$catID = isset($_GET['catID']) ? $_GET['catID'] : null;
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
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="section-block" id="basicform">
                            <p>Fill in the information below to add a new product...</p>
                        </div>
                        <div class="card">
                            <div class="card-body">
                        <!-- Add your form for adding a new product here -->
                        <form action="product-add_p.php?catID=<?php echo $catID; ?>" method="POST">
                            <input type="hidden" name="catID" value="<?php echo $catID; ?>">
                            <div class="form-group">
                            <label for="inputText3" class="col-form-label" >Product Name</label>
                            <input id="inputText3" type="text" class="form-control" name="ProName" required>
                            </div>
                            <div class="form-group">
                                <label for="inputText3" class="col-form-label" >Product Cost</label>
                                <input id="inputText3" type="text" class="form-control" name="ProCost" required>
                            </div>
                            <div class="form-group">
                        <label for="inputText3" class="col-form-label" >Product Price</label>
                        <input id="inputText3" type="text" class="form-control" name="ProPrice" required>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                        <label for="inputText3" >Storage</label>
                        <input id="inputText3" type="text" class="form-control" name="Storage" required>
                    </div>
                    <div class="col-md-6">
                        <label for="inputText3" >Shelf Life</label>
                        <input id="inputText3" type="text" class="form-control" name="ShelfLife" required>
                    </div>
                </div>
                <div class="form-group">
                        <label for="Textarea" >Product Description</label>
                        <textarea class="form-control" id="Textarea" rows="3" name="ProDesc" required></textarea>
                </div>
                <div class="form-group">
                        <label for="Textarea" >Ingredient</label>
                        <textarea class="form-control" id="Textarea" rows="3" name="Ingredient" required></textarea>
                </div>

                <div class="form-group">
                        <label for="productPicture">Product Picture</label>
                        <input type="file" class="form-control-file" id="productPicture" name="#">
                </div>
                            
                            <button type="submit" name="save" class="btn btn-primary">Add Product</button>
                            <button type="reset"  name="cancel" class="btn btn-danger">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
</body>

</html>