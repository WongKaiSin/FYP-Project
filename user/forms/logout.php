<?php
    session_start();

    // Check if the logout button is clicked
    if(isset($_POST['logout'])) {
        session_start();
        session_destroy();

        // Redirect to the login page or any other page after logout
        header('location: ../index.php');
        exit;
    }
?>