<?php
session_start();
require("lib/db.php");
require("lib/function.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("lib/head.php"); ?>
    <style>
    html,
    body {
        height: 100%;
    }

    body {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
    }
    </style>

    <title>Forgot Password</title>
</head>

<body>
<div class="splash-container">
        <div class="card">
            <div class="card-header text-center"><img class="logo-img" src="assets/images/logo.jpg" alt="logo" height="100px" weight="20px"></div>
            <div class="card-body">
                <form action="login_p.php" method="post">
                    <p>Don't worry, we will send your new password to your email.</p>
                    <div class="form-group">
                        <input class="form-control form-control-lg" type="email" name="PassEmail" required="" placeholder="Your Email" autocomplete="off">
                    </div>
                    <div class="form-group pt-1"><input type="submit" name="BtnPass" class="btn btn-primary float-right" value="Send Password"></div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
