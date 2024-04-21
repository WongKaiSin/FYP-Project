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
    $adUser = $_SESSION['adUser'];
    $currentDateTime = date("Y-m-d H:i:s");

    $ProName = $_POST["ProName"];
    $ProCost = $_POST["ProCost"];
    $ProPrice = $_POST["ProPrice"];
    $Storage = $_POST["Storage"];
    $ShelfLife = $_POST["ShelfLife"];
    $ProDesc = $_POST["ProDesc"];
    $Ingredient = $_POST["Ingredient"];

    // Insert data into the database
    $sql = "INSERT INTO product (ProName, ProAddPerson, ProCost, ProPrice, Storage, ShelfLife, ProDesc, Ingredient, ProAddDate) 
            VALUES ('$ProName', '$adUser', '$ProCost', '$ProPrice', '$Storage', '$ShelfLife', '$ProDesc', '$Ingredient','$currentDateTime')";


    // Execute the first insert query
    if ($db_conn->query($sql) === TRUE) {
        // Retrieve the last inserted ID
        $last_id = $db_conn->insert_id;

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
