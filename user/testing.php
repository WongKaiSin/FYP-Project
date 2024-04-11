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
</head>
<body>

<h1>Menu</h1>

<form method="get" action="">
    <label for="CatID">Select a category:</label>
    <select name="CatID" id="CatID">
        <option value="">All</option> <!-- Option to show all categories -->
        <option value="1">Drinks</option>
        <option value="2">Food</option>
        <!-- Add more options as needed with corresponding category IDs -->
    </select>
    <input type="submit" value="Filter">
</form>

<?php

if ($pro_query->num_rows > 0) {
    // Output data of each row
    while($row = $pro_query->fetch_assoc()) {
        echo "<p>" . $row["ProName"] . "</p>";
        echo "<p>" . $row["ProPrice"] . "</p>";
    }
} else {
    echo "0 results";
}

?>

</body>
</html>