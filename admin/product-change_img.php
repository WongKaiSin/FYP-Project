<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");
mysqli_select_db($db_conn, "bagel");
$ProID = isset($_GET['ProID']) ? $_GET['ProID'] : null;

$sql_fetch_pro_name = "SELECT ProName FROM product WHERE ProID = '$ProID'";
$result_fetch_pro_name = $db_conn->query($sql_fetch_pro_name);

if ($result_fetch_pro_name->num_rows > 0) {
    $row = $result_fetch_pro_name->fetch_assoc();
    $ProName = $row['ProName'];
} else {
    // Handle case where ProName is not found
    $ProName = ""; // Set a default value or handle error as needed
}

if(isset($_POST['save'])) {
   
    $filename = $_FILES["ProUrl"]["name"];
    $tempname = $_FILES["ProUrl"]["tmp_name"];
    $folder = "../upload/product/";

    $fileExt = pathinfo($filename, PATHINFO_EXTENSION);

    // Construct the filename using the username and file extension
    $newFilename = $ProName . '.' . $fileExt;

    // Move the uploaded file before inserting data into the database
    if (!empty($filename) && move_uploaded_file($tempname, $folder . $newFilename)) {
        
        $sql = "UPDATE `product` SET ProUrl = '$newFilename' WHERE ProID = '$ProID'";
        $result = $db_conn->query($sql);

    } else {
        echo "<h3>Failed to upload image!</h3>";
    }
} 

if ($result) {
    // Query executed successfully
    echo '<script type="text/javascript">
    alert("Image uploaded successfully and database updated!");
    </script>';
    header("Location: product-desc.php?ProID=$ProID");
    exit();
} else {
    // Error occurred
    echo "Error updating admin information: " . $db_conn->error;
}
?>


<!doctype html>
<html lang="en">

<head>
    <title>Change Product Image</title>
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
                            <h2 class="pageheader-title">Change Product Image</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="productPicture">Product Picture</label>
                                    <input type="file" class="form-control-file" id="ProUrl" name="ProUrl" required>
                                </div>               
                                <button type="submit" name="save" class="btn btn-primary">Upload</button>
                                <a href=" product-desc.php?ProID=<?php echo $ProID ?>" class="btn btn-danger">Cancel</a>
                            </form>
               
                    </div>
                </div>
            </div>
</body>

</html>