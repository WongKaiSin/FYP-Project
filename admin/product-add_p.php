<?php
session_start();
include("lib/db.php");

if(isset($_GET['catID']) && is_numeric($_GET['catID'])) {
    $catID = $_GET['catID'];
} else {
    // Handle if category_id is not set or not numeric
    $error_message = "Invalid category ID";
    // Redirect or display an error message as needed
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save"])){
    // Retrieve form data
    $adName = $_SESSION["adName"];
    $currentDateTime = date("Y-m-d H:i:s");

    $ProName = $_POST["ProName"];
    $ProCost = $_POST["ProCost"];
    $ProPrice = $_POST["ProPrice"];
    $Storage = $_POST["Storage"];
    $ShelfLife = $_POST["ShelfLife"];
    $ProDesc = $_POST["ProDesc"];
    $Ingredient = $_POST["Ingredient"];
    $stock= $_POST["stock"];

    

    // Insert data into the database
    $sql = "INSERT INTO product (ProName, ProAddPerson, ProCost, ProPrice, Storage, ShelfLife, ProDesc, Ingredient, ProAddDate, ProStock) 
            VALUES ('$ProName', '$adName', '$ProCost', '$ProPrice', '$Storage', '$ShelfLife', '$ProDesc', '$Ingredient','$currentDateTime','$stock')";

    // Execute the insert query
    if ($db_conn->query($sql) === TRUE) {
        // Retrieve the last inserted ID
        $last_id = $db_conn->insert_id;

        $productFolder = "../upload/product/$last_id";
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
                            VALUES ('$last_id', '$imagename', '$fileExt', '$currentDateTime', '$adName')";

                // Execute the image insert query
                if ($db_conn->query($image_sql) === FALSE) {
                    $error_message = "Error: " . $image_sql . "<br>" . $db_conn->error;
                    // Handle the error
                }
            } else {
                $error_message = "Error uploading image: $filename";
                // Handle the error
            }
        }

        // Fetch CatName based on CatID
        $sql_fetch_cat_name = "SELECT CatName FROM category WHERE catID = '$catID'";
        $result_fetch_cat_name = $db_conn->query($sql_fetch_cat_name);

        if ($result_fetch_cat_name->num_rows > 0) {
            // Fetch CatName
            $row = $result_fetch_cat_name->fetch_assoc();
            $CatName = $row["CatName"];

            // Insert data into the product_cat table
            $sql_product_cat = "INSERT INTO product_cat (ProID, CatID, ProName, CatName)
                                VALUES ('$last_id', '$catID', '$ProName', '$CatName')";

            // Execute the second insert query
            if ($db_conn->query($sql_product_cat) === TRUE) {
                // Set success message
                $success_message = "New record created successfully";

                // Redirect after submission
                echo '<script type="text/javascript">
                        alert("'.$ProName.' saved");
                        window.location.href = "product-view.php";
                      </script>';
                exit();
            } else {
                $error_message = "Error: " . $sql_product_cat . "<br>" . $db_conn->error;
            }
        } else {
            $error_message = "Category not found";
        }
    } else {
        $error_message = "Error: " . $sql . "<br>" . $db_conn->error;
    }

    // Close the database connection
    $db_conn->close();
}
?>
