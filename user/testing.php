<?php
include("lib/db.php");

// Assuming $db_conn is your database connection object

// Check if a category is selected
if(isset($_GET['CatID'])) {
    $category = $_GET['CatID'];
    // Prepare the SQL statement with a parameterized query to prevent SQL injection
    $stmt = $db_conn->prepare("SELECT product_cat.ProName, product.ProPrice 
                               FROM product_cat 
                               JOIN product ON product_cat.ProID = product.ProID 
                               WHERE product_cat.CatID = ?");
    $stmt->bind_param("s", $category); // "s" indicates a string parameter
    $stmt->execute();
    $pro_query = $stmt->get_result();
} else {
    // If no category is selected, fetch all products
    $stmt = $db_conn->prepare("SELECT product_cat.ProName, product.ProPrice 
                               FROM product_cat 
                               JOIN product ON product_cat.ProID = product.ProID");
    $stmt->execute();
    $pro_query = $stmt->get_result();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Menu</title>
    <style>
        .container {
            display: flex;
        }
        aside {
            width: 20%; /* Adjust width as needed */
            padding: 20px;
        }
        .product-display {
            width: 80%; /* Adjust width as needed */
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
        }
        .product-item {
            width: calc(25% - 20px); /* Adjust width and margin as needed for 4 items per row */
            margin: 10px;
            padding: 10px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

<h1>Menu</h1>

<div class="container">
    <aside>
        <ul>
        <li><a href="?">All Products</a></li> <!-- Added link for All Products -->
            <li><a href="?CatID=1">Drinks</a></li>
            <li><a href="?CatID=2">Food</a></li>
            <!-- Add more options as needed with corresponding category IDs -->
        </ul>
    </aside>

    <div class="product-display">
        <?php
        if ($pro_query->num_rows > 0) {
            // Output data of each row
            while($row = $pro_query->fetch_assoc()) {
                echo '<div class="product-item">';
                echo "<p>" . $row["ProName"] . "</p>";
                echo "<p>" . $row["ProPrice"] . "</p>";
                echo '</div>';
            }
        } else {
            echo "0 results";
        }
        ?>
    </div>
</div>

</body>
</html>
