<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save"])){
    

    // Retrieve form data
   
    $currentDateTime = date("Y-m-d H:i:s");
    $adName = $_SESSION["adName"];
    $catName = $_POST["catName"];

    // Insert data into the database
    $sql = "INSERT INTO category (catAddPerson, catName, catAddDate) 
            VALUES ('$adName', '$catName', '$currentDateTime')";

if ($db_conn->query($sql) === TRUE) {
    // Set success message
    $success_message = "New record created successfully";
    
    // Redirect after submission
    echo '<script type="text/javascript">
                alert("'.$catName.' saved");
                window.location.href = "product-view.php";
          </script>';
    exit();
} else {
    
    $error_message = "Error: " . $sql . "<br>" . $db_conn->error;
}

// Close the database connection
$db_conn->close();
}

?>
<!doctype html>
<html lang="en">

<head>
    <title>Add Product Category</title>
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
                            <h2 class="pageheader-title">Add Product Category</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="product-view.php" class="breadcrumb-link">View Product List</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Add Product Category</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="section-block" id="basicform">
                            <p>Fill in the information below to add a new product category...</p>
                        </div>
                        <div class="card">
                            <div class="card-body">
                        <!-- Add your form for adding a new product here -->
                            <form action="product-category-add.php" method="POST">
                            <div class="form-group">
                            <label for="inputText3" class="col-form-label" >Category Name</label>
                            <input id="inputText3" type="text" class="form-control" name="catName" required>
                            </div>                     
                            
                            <button type="submit" name="save" class="btn btn-primary">Add Category</button>
                            <button type="reset"  name="cancel" class="btn btn-danger">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
</body>

</html>