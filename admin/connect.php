<?php

    // Creating a database connection

    $connection = mysqli_connect("localhost", "root", "", "bagel");
    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }


    // Selecting a database 

    $db_select = mysqli_select_db($connection, "bagel");
    if (!$db_select) {
        die("Database selection failed: " . mysqli_connect_error());
    }

?>