<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_catalogue"])) {
    $cataID = $_POST['cataID'];
    $newCataName = $_POST['newCataName'];
    $currentDateTime = date("Y-m-d H:i:s");
    $adName = $_SESSION["adName"];

    $sql_update_catalogue = "UPDATE catalogue SET cataName = '$newCataName' WHERE cataID = '$cataID'";
    if ($db_conn->query($sql_update_catalogue) === TRUE) {
        // Update catalogue name in the category_cata table
        $sql_update_category_cata = "UPDATE category_cata SET cataName = '$newCataName' WHERE cataID = '$cataID'";
        if ($db_conn->query($sql_update_category_cata) === TRUE) {
            header("Location: product-view.php");
            exit();
        } else {
            echo "Error updating category_cata table: " . $db_conn->error;
        }
    } else {
        echo "Error updating catalogue table: " . $db_conn->error;
    }
}

// Fetch category details based on catID from the URL
$cataID = isset($_GET['cataID']) ? $_GET['cataID'] : null;
$query = "SELECT * FROM catalogue WHERE cataID = '$cataID'";
$result = $db_conn->query($query);
$row = $result->fetch_assoc();
?>

<!doctype html>
<html lang="en">

<head>
    <title><?php echo $row['cataName']; ?> - Edit Catalogue</title>
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
                            <h2 class="pageheader-title"><?php echo $row['cataName']; ?> - Edit Catalogue</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="product-view.php" class="breadcrumb-link">View Product List</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Edit Product Catalogue</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="section-block" id="basicform">
                            <p>Edit the information below to edit the catalogue...</p>
                        </div>    
                        <div class="card">
                            <div class="card-body">
                                <form action="product-catalogue-edit.php" method="POST">
                                    <input type="hidden" name="cataID" value="<?php echo $row['cataID']; ?>">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="newCataName" name="newCataName" value="<?php echo $row['cataName']; ?>"> 
                                    </div>
                                    <button type="submit" name="edit_catalogue" class="btn btn-primary">Update</button>
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
