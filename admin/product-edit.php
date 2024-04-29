<?php
session_start();

include("lib/head.php");
include("lib/db.php");

// Retrieve the product ID from the URL
$ProID = isset($_GET['ProID']) ? $_GET['ProID'] : null;

// Fetch product details from the database based on the product ID
$query = "SELECT * FROM product WHERE ProID = '$ProID'";
$result = $db_conn->query($query);

if ($result && $result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    // Handle case where product is not found
    echo "Product not found!";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentDateTime = date("Y-m-d H:i:s");
    $adUser = $_SESSION['adUser'];
    $newName = $_POST['newName'];
    $newPrice = $_POST['newPrice'];
    $newCost = $_POST['newCost'];
    $newDesc = $_POST['newDesc'];
    $newStorage = $_POST["newStorage"];
    $newShelfLife = $_POST["newShelfLife"];
    $newIngredient = $_POST["newIngredient"];
    $newCategory = $_POST['newCategory'];

    // Fetch category name based on category ID
    $sql_fetch_cat_name = "SELECT CatName FROM category WHERE CatID = '$newCategory'";
    $result_fetch_cat_name = $db_conn->query($sql_fetch_cat_name);

    if ($result_fetch_cat_name->num_rows > 0) {
        // Fetch CatName
        $row = $result_fetch_cat_name->fetch_assoc();
        $newCategoryName = $row["CatName"];

        // Update the product details in the product table
        $updateProductQuery = "UPDATE product 
                                SET ProName='$newName', 
                                    ProPrice='$newPrice', 
                                    ProCost='$newCost', 
                                    ProDesc='$newDesc', 
                                    ProModifyDate='$currentDateTime', 
                                    ProModifyPerson='$adUser', 
                                    Ingredient='$newIngredient',
                                    Storage='$newStorage', 
                                    ShelfLife='$newShelfLife'
                                WHERE ProID='$ProID'";

        // Update the product details in the product_cat table
        $updateProductCatQuery = "UPDATE product_cat 
                                    SET ProName='$newName', 
                                        CatName='$newCategoryName', 
                                        CatID='$newCategory' 
                                    WHERE ProID='$ProID'";

        // Execute both queries
        if ($db_conn->query($updateProductQuery) === TRUE && $db_conn->query($updateProductCatQuery) === TRUE) {
            // Redirect back to the product description page
            header("Location: product-desc.php?ProID=$ProID");
            exit();
        } else {
            // Handle errors, if any
            echo "Error updating product: " . $db_conn->error;
        }
    } else {
        // Handle case where category is not found
        echo "Category not found!";
        exit;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <title><?php echo $product['ProName']; ?> - Edit Product</title>
    
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
                            <h2 class="pageheader-title"><?php echo $product['ProName']; ?> - Edit Product</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="product-view.php" class="breadcrumb-link">View Product List</a></li>
                                        <li class="breadcrumb-item"><a href="product-desc.php?ProID=<?php echo $ProID; ?>" class="breadcrumb-link">Product Description</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="section-block" id="basicform">
                            <p>Edit the information below to edit the product...</p>
                    </div> 
                        <div class="card">
                            <div class="card-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label for="newName">Product Name:</label>
                                        <input type="text" class="form-control" id="newName" name="newName" value="<?php echo $product['ProName']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="newCategory">Category:</label>
                                        <select class="form-control" id="newCategory" name="newCategory">
                                            <?php
                                            // Fetch categories 
                                            $catQuery = "SELECT * FROM category";
                                            $catResult = $db_conn->query($catQuery);

                                            if ($catResult && $catResult->num_rows > 0) {
                                                while ($row = $catResult->fetch_assoc()) {
                                                    // Populate dropdown options with category names and IDs
                                                    echo "<option value='" . $row['catID'] . "'>" . $row['catName'] . "</option>";
                                                }
                                            } else {
                                                echo "<option value=''>No categories found</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="newPrice">Price:</label>
                                        <input type="text" class="form-control" id="newPrice" name="newPrice" value="<?php echo $product['ProPrice']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="newCost">Cost:</label>
                                        <input type="text" class="form-control" id="newCost" name="newCost" value="<?php echo $product['ProCost']; ?>">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="newStorage">Storage</label>
                                            <input type="text" class="form-control" name="newStorage" value="<?php echo $product['Storage']; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="newShelfLife">Shelf Life</label>
                                            <input type="text" class="form-control" name="newShelfLife" value="<?php echo $product['ShelfLife']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="newDesc">Description:</label>
                                        <textarea class="form-control" id="newDesc" name="newDesc"><?php echo $product['ProDesc']; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="newDesc">Ingredient:</label>
                                        <textarea class="form-control" id="newIngredient" name="newIngredient"><?php echo $product['Ingredient']; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="productPicture">Product Picture</label>
                                        <input type="file" class="form-control-file" id="productPicture" name="#">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
