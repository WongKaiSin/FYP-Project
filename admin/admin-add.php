<?php
session_start();

include("lib/db.php");
include("lib/function.php");

$func = new Functions;

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["savebtn"])){
    // Retrieve form data
    $adUser = $_POST["adEmail"];
    $adName = $_POST["adName"];
    $adEmail = $_POST["adEmail"];
    $adTel = $_POST["adTel"];
    $adPass = $func->PassSign($adUser, $_POST["adPass"]);
    $adType = $_POST["inputAdminType"];
    $adAdd = $_POST["adAdd"];
    $adCountry = $_POST["adCountry"];
    $adState = $_POST["adState"];
    $adCity = $_POST["adCity"];
    $adPostcode = $_POST["adPostcode"];
    $currentDateTime = date("Y-m-d H:i:s");


        // Insert data into the database
        $sql = "INSERT INTO admin (adUser, adName, adEmail, adTel, adPass, adType, adAdd, adCountry, adState, adCity, adPostcode, adStatus, AdminAddDate) 
            VALUES ('$adEmail', '$adName', '$adEmail', '$adTel', '$adPass', '$adType', '$adAdd', '$adCountry', '$adState', '$adCity', '$adPostcode', '1', '$currentDateTime')";

    if ($db_conn->query($sql) === TRUE) {
        // Get the last inserted adID
        $adID = $db_conn->insert_id;

        $filename = $_FILES["adLogo"]["name"];
        $tempname = $_FILES["adLogo"]["tmp_name"];
        $folder = "../upload/admin/";

        $fileExt = pathinfo($filename, PATHINFO_EXTENSION);
        $newFilename = $adID . '.' . $fileExt; // Use adID as the new filename

        // Move the uploaded file
        if (!empty($filename) && move_uploaded_file($tempname, $folder . $newFilename)) {
            echo "<h3> Image uploaded successfully!</h3>";

            // Update the admin record with the profile picture filename
            $sql_update = "UPDATE admin SET adLogo = '$newFilename' WHERE adID = '$adID'";
            if ($db_conn->query($sql_update) === TRUE) {
                echo '<script type="text/javascript">
                        alert("' . $adName . ' saved");
                        window.location.href = "admin-view.php";
                      </script>';
                exit();
            } else {
                echo "<h3>Error updating image: " . $db_conn->error . "</h3>";
            }
        } else {
            echo "<h3>Failed to upload image!</h3>";
        }
    } else {
        echo "<h3>Error: " . $sql . "<br>" . $db_conn->error . "</h3>";
    }

    // Close the database connection
    $db_conn->close();
}
?>



<!doctype html>
<html lang="en">

<head>
    <?php include("lib/head.php"); ?>
    <title>Add Admin</title>
    <script>
    function checkPassword() {
        var password = document.getElementById("adPass").value;
        var confirm_password = document.getElementById("confirm_password").value;

        if (password !== confirm_password) {
            alert("Passwords do not match!");
            return false;
        }
        return true;
    }
</script>
</head>
<style>
 
</style>
<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
        <?php 
            include("lib/navbar.php");
            include("lib/sidebar.php");
        ?>


        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                <!-- ============================================================== -->
                <!-- pageheader -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h2 class="pageheader-title">Add New Admin</h2>

                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="admin-view.php" class="breadcrumb-link">View Admin List</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Add New Admin</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end pageheader -->
                <!-- ============================================================== -->
                <div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="section-block" id="basicform">
            <p>Fill in the information below to add a new administrator...</p>
        </div>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="admin-add.php" enctype="multipart/form-data"onsubmit="return checkPassword();">

                    <div class="form-group">
                        <label for="inputText3" class="col-form-label" >Admin Name</label>
                        <input id="inputText3" type="text" class="form-control" name="adName" required>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail" >Email address</label>
                        <input id="inputEmail" type="email" placeholder="name@example.com" class="form-control" name="adEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="inputText3" class="col-form-label" >Phone number</label><small> (xxx-xxx xxxx)</small>
                        <input id="inputText3" type="tel" class="form-control" name="adTel" pattern="[0-9]{3}-[0-9]{3} [0-9]{4}" required>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword">Password</label>
                        <input id="adPass" type="password" class="form-control" name="adPass" required>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword">Confirm Password</label>
                        <input id="confirm_password" type="password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="inputAdminType">Admin Type</label>
                        <select id="inputAdminType" class="form-control" name="inputAdminType" required>
                            <option value="Admin">Admin</option>
                            <option value="SuperAdmin">SuperAdmin</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlTextarea1" >Address</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="adAdd" required></textarea>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="inputPostcode">Postcode</label>
                            <input id="inputPostcode" type="text" class="form-control" pattern="\d{5}" name="adPostcode" required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputCity">City</label>
                            <input id="inputCity" type="text" class="form-control" name="adCity" required>
                        </div>
                    </div>
                    <div class="form-group row">
                    <div class="col-md-6">
                        <label for="inputState" >State</label>
                        <input id="inputState" type="text" class="form-control" name="adState" required>
                    </div>
                    
                        <div class="col-md-6">
                        <label for="inputCounty" >Country</label>
                        <input id="inputCounty" type="text" class="form-control" name="adCountry" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="profilePicture">Profile Picture</label>
                    <input type="file" class="form-control-file" id="profilePicture" name="adLogo" required>
                </div>                                        
                    <button type="submit"  name="savebtn" class="btn btn-primary">Add Admin</button>
                    <button type="reset"  name="registerbtn" class="btn btn-danger">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>


</body>
 
</html>