<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_category"])) {
    $catID = $_POST['catID'];
    $newCatName = $_POST['newCatName'];
    $currentDateTime = date("Y-m-d H:i:s");
    $adName = $_SESSION["adName"];

    // Update category name in the category table
    $sql_update_category = "UPDATE category SET catName = '$newCatName', catModifyDate = '$currentDateTime', catModifyPerson='$adName' WHERE catID = '$catID'";
    if ($db_conn->query($sql_update_category) === TRUE) {
        // Update category name in the product_cat table
        $sql_update_product_cat = "UPDATE product_cat SET CatName = '$newCatName' WHERE CatID = '$catID'";
        if ($db_conn->query($sql_update_product_cat) === TRUE) {
            // Redirect back to the product list page
            header("Location: product-view.php");
            exit();
        } else {
            echo "Error updating product_cat table: " . $db_conn->error;
        }
    } else {
        echo "Error updating category table: " . $db_conn->error;
    }
}

// Fetch category details based on catID from the URL
$catID = isset($_GET['catID']) ? $_GET['catID'] : null;
$query = "SELECT * FROM category WHERE catID = '$catID'";
$result = $db_conn->query($query);
$categoryRow = $result->fetch_assoc();
?>

<!doctype html>
<html lang="en">

<head>
    <title><?php echo $categoryRow['catName']; ?> - Edit Category</title>
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
                            <h2 class="pageheader-title"><?php echo $categoryRow['catName']; ?> - Edit Category</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="product-view.php" class="breadcrumb-link">View Product List</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Edit Product Category</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="section-block" id="basicform">
                            <p>Edit the information below to edit the category...</p>
                    </div>    
                    <div class="card">
                            <div class="card-body">
                                <form action="product-category-edit.php" method="POST">
                                    <input type="hidden" name="catID" value="<?php echo $categoryRow['catID']; ?>">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="newCatName" name="newCatName" value="<?php echo $categoryRow['catName']; ?>"> 
                                    </div>
                                    <button type="submit" name="edit_category" class="btn btn-primary">Update</button>
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
