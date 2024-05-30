<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");

// Retrieve the product ID from the session
$ProID = $_SESSION["ProID"];
$adName = $_SESSION["adName"];
$currentDateTime = date("Y-m-d H:i:s");

// Fetch the product name based on the ProID
$sql_fetch_pro_name = "SELECT ProName FROM product WHERE ProID = '$ProID'";
$result_fetch_pro_name = $db_conn->query($sql_fetch_pro_name);

if ($result_fetch_pro_name->num_rows > 0) {
    $row = $result_fetch_pro_name->fetch_assoc();
    $ProName = $row['ProName'];
} else {
    // Handle case where ProName is not found
    $ProName = ""; // Set a default value or handle error as needed
}

// Handle image upload and database update upon form submission
if(isset($_POST['save'])) {
    
    
    $productFolder = "../upload/product/$ProName";
    if (!file_exists($productFolder)) {
        mkdir($productFolder, 0777, true);
    }

    $imageFiles = $_FILES["ProImg"];


    foreach ($imageFiles["tmp_name"] as $key => $tmp_name) {
        $filename = $imageFiles["name"][$key];
        $tempname = $tmp_name;

        $fileExt = pathinfo($filename, PATHINFO_EXTENSION);
        // Construct the filename using a sequential number
        $newFilename = ($key + 1) . '.' . $fileExt;
        $imagename=$key + 1;

        // Move the uploaded file before inserting data into the database
        if (!empty($filename) && move_uploaded_file($tempname, "$productFolder/$newFilename")) {
            // Insert image information into the database
            $image_sql = "INSERT INTO product_image (ProID, ImageName, ImageExt, ImgAddDate, ImgAddPerson) 
                        VALUES ('$ProID', '$imagename', '$fileExt', '$currentDateTime', '$adName')";

            // Execute the image insert query
            if ($db_conn->query($image_sql) === FALSE) {
                $error_message = "Error: " . $image_sql . "<br>" . $db_conn->error;
                // Handle the error
            }
        } else {
            $error_message = "Error uploading image: $filename";
            echo '<script type="text/javascript">
            alert("Image uploaded unsuccessfully");
            </script>';
            header("Location: product-desc.php?ProID=$ProID");
            // Handle the error
        }
    }

    // If no errors occurred, redirect back to product description page
    if (!isset($error_message)) {
        echo '<script type="text/javascript">
        alert("Image uploaded successfully and database updated!");
        </script>';
        header("Location: product-desc.php?ProID=$ProID");
        exit();
    }
} 

?>

<!doctype html>
<html lang="en">

<head>
    <title>Add <?php echo $ProName; ?> Image</title>
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
                            <h2 class="pageheader-title">Add <?php echo $ProName; ?> Image</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="productPicture">Product Pictures</label>
                                        <input type="file" class="form-control-file" id="ProImg" name="ProImg[]" multiple required>
                                    </div>               
                                    <button type="submit" name="save" class="btn btn-primary">Upload</button>
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
