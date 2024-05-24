<?php
session_start();
include("lib/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ProID = $_SESSION["ProID"];


    // Delete related records in product_cat table
    $sql_delete_product_cat = "UPDATE product_cat SET Active=0 WHERE ProID = '$ProID'";
    if ($db_conn->query($sql_delete_product_cat) === TRUE) {
        // Now delete the product from the product table
        $sql_delete_product = "UPDATE product SET isUp=0 WHERE ProID = '$ProID'";
        if ($db_conn->query($sql_delete_product) === TRUE) {
            header("Location: product-view.php");
            exit();
        } else {
            echo "Error deleting product: " . $db_conn->error;
        }
    } else {
        echo "Error deleting related product categories: " . $db_conn->error;
    }
}
?>
