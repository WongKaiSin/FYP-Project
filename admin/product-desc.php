<?php
session_start();

include("lib/head.php");
include("lib/db.php");

// Retrieve the product ID from the URL
$ProID = isset($_GET['ProID']) ? $_GET['ProID'] : null;
$_SESSION["ProID"] = $ProID;

// Fetch product details from the database based on the product ID
$query = "SELECT * FROM product WHERE ProID = '$ProID'";
$result = $db_conn->query($query);

if ($result && $result->num_rows > 0) {
    $product = $result->fetch_assoc();
    // Calculate profit
    $cost = $product['ProCost'];
    $price = $product['ProPrice'];
    $profit = $price - $cost;
} else {
    // Handle case where product is not found
    echo "Product not found!";
    exit;
}

// Handle form submission to update product stock
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $stock = isset($_POST['stock']) ? $_POST['stock'] : null;
    $currentDateTime = date("Y-m-d H:i:s");
    $adName = $_SESSION["adName"];
    // Validate and sanitize input data

    // Update product details in the database
    $sql_update = "UPDATE product SET ProStock = '$stock', ProModifyDate='$currentDateTime', ProModifyPerson='$adName' WHERE ProID = '$ProID'";
    if ($db_conn->query($sql_update) === TRUE) {
        // Redirect back to the product description page
        header("Location: product-desc.php?ProID=$ProID");
        exit();
    } else {
        // Handle errors, if any
        echo "Error updating product: " . $db_conn->error;
    }
}
?>
<script type="text/javascript">
    function confirmation() {
        return confirm("Do you want to delete this product?");
    }
</script>

<!doctype html>
<html lang="en">

<head>
    <title><?php echo $product['ProName']; ?> - Product Description</title>
    
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
                            <h2 class="pageheader-title"><?php echo $product['ProName']; ?> - Product Description</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="product-view.php" class="breadcrumb-link">View Product List</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Product Description</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                            <div class="carousel-inner">
                                            <?php
                                                $ProID = $_GET['ProID'];

                                                $img_sql = "SELECT `ImageName`, `ImageExt` FROM product_image WHERE `ProID` = ".$ProID."";
                                                $img_query = $db_conn->query($img_sql);

                                                $first = true;
                                                while ($img_row = $img_query->fetch_assoc()) {
                                                    $ImageName = $img_row['ImageName'];
                                                    $ImageExt = $img_row['ImageExt'];
                                                    $image_url = "../upload/product/" . $product['ProID'] . "/" . $ImageName . "." . $ImageExt;
                                                    //echo "Image URL: $image_url <br>"; // Debugging
                                                ?>
                                                    <div class="carousel-item <?php if ($first) { echo 'active'; $first = false; } ?>">
                                                        <img class="d-block w-100" style="height: 300px; weight: 100%; margin-top:60px;" src="<?php echo $image_url; ?>" alt="Product image">
                                                    </div>
                                                <?php
                                                }

                                                // If no images found for the product, display placeholder image and alert user
                                                if ($img_query->num_rows === 0) {
                                                    echo "<img src='$SiteUrl/user/assets/img/no-image.png'>";
                                                    echo '<script type="text/javascript">
                                                        alert("No images found for the product with ID: ' . $ProID . ',Please add some image");
                                                        window.location.href = "product-change_img.php";
                                                    </script>';

                                                }
                                            ?>
                                            </div>
                                            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                    <div class="tab-regular">
                                        <ul class="nav nav-tabs " id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="main-tab" data-toggle="tab" href="#main" role="tab" aria-controls="main" aria-selected="true"><?php echo $product['ProName']; ?></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="desc-tab" data-toggle="tab" href="#desc" role="tab" aria-controls="desc" aria-selected="false">Description</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="add-info-tab" data-toggle="tab" href="#add-info" role="tab" aria-controls="add-info" aria-selected="false">Additional Info</a>
                                            </li>
                                        </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th>Price</th>
                                                        <td>RM <?php echo $product['ProPrice']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Cost</th>
                                                        <td>RM <?php echo $product['ProCost']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Profit</th>
                                                        <td>RM <?php echo $profit; ?></td>
                                                    </tr>
                                                        <th>Stock</th>
                                                            <td>
                                                                <form class="form-inline" method="post" action="">
                                                                    <div class="form-group mr-2">
                                                                        <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $product['ProStock']; ?>">
                                                                    </div>
                                                                    <button type="submit" class="btn btn-primary btn-xs">Update Stock</button>
                                                                </form>
                                                            </td>
                                                        <tr>
                                                            <th>Add Person</th>
                                                            <td><?php echo $product['ProAddPerson']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Last Modify</th>
                                                            <td><?php echo $product['ProModifyPerson']; ?> <?php echo $product['ProModifyDate']; ?></td>
                                                        </tr>
                                                </tbody>
                                            </table>
                                    </div>
                                    <div class="tab-pane fade" id="desc" role="tabpanel" aria-labelledby="desc-tab">
                                        <p><?php echo $product['ProDesc']; ?></p>
                                    </div>
                                    <div class="tab-pane fade" id="add-info" role="tabpanel" aria-labelledby="add-info-tab">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Ingredient</th>
                                                    <td><?php echo $product['Ingredient']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Storage</th>
                                                    <td><?php echo $product['Storage']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Shelf Life</th>
                                                    <td><?php echo $product['ShelfLife']; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                         
                        <form method="post" action="delete_pro.php">
                            <!-- Product details and tabs -->
                            <div class="col-sm-12 pl-0 text-right">
                                <p style="padding: 20px;">      
                                    <a class="btn btn-success" href="product-edit.php">Edit</a>
                                    <button type="submit" class="btn btn-secondary" name="del" onclick="return confirmation();">Delete</button>
                                </p>
                            </div>
                        </form>
                                   </div>
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
