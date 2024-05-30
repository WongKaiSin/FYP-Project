<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");

if(isset($_POST['save'])) {
    $adUser = $_SESSION["adUser"];
    $filename = $_FILES["adLogo"]["name"];
    $tempname = $_FILES["adLogo"]["tmp_name"];
    $folder = "../upload/admin/";

    $fileExt = pathinfo($filename, PATHINFO_EXTENSION);

    // Construct the filename using the username and file extension
    $sql = "SELECT adID FROM `admin` WHERE adUser = '$adUser'";
    $result = $db_conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $adID = $row["adID"];

        // Construct the new filename using the adID and the new file extension
        $newFilename = $adID . '.' . $fileExt;

        // Move the uploaded file before inserting data into the database
        if (!empty($filename) && move_uploaded_file($tempname, $folder . $newFilename)) {
            $sql = "UPDATE `admin` SET adLogo = '$newFilename' WHERE adUser = '$adUser'";
            $result = $db_conn->query($sql);

            if ($result) {
                // Query executed successfully
                echo '<script type="text/javascript">
                alert("Image uploaded successfully and database updated!");
                window.location.href = "admin-profile.php";
                </script>';
                exit();
            } else {
                // Error occurred
                echo "Error updating admin information: " . $db_conn->error;
            }
        } else {
            echo "<h3>Failed to upload image!</h3>";
        }
    } else {
        echo "<h3>No admin found with the specified username!</h3>";
    }
}
?>


<!doctype html>
<html lang="en">

<head>
    <title>Change Profile Image</title>
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
                            <h2 class="pageheader-title">Change Profile Image</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="profilePicture">Profile Picture</label>
                                    <input type="file" class="form-control-file" id="profilePicture" name="adLogo" required>
                                </div>                 
                                <button type="submit" name="save" class="btn btn-primary">Upload</button>
                                <a href="admin-profile.php" class="btn btn-danger">Cancel</a>
                            </form>
               
                    </div>
                </div>
            </div>
</body>

</html>