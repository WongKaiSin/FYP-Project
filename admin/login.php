<?php
session_start();
require("lib/db.php");
require("lib/function.php");
$func = new Functions;
$msg = (isset($_GET['msg']) ? $_GET["msg"] : "");
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
    <title>Admin Login</title>
</head>

<body>
    <!-- ============================================================== -->
    <!-- login page  -->
    <!-- ============================================================== -->
    <div class="splash-container">
        <div class="card ">
            <div class="card-header text-center"><a href="../index.php"><img class="logo-img" src="assets/images/logo.jpg" alt="logo" height=155px weight=35px></a><span class="splash-description">Please sign in.</span></div>
            <div class="card-body">
                <?php
                if($msg == 1)
                {
                    $disp_msg = "You have enter wrong username or password."; $type = "error";
                    echo $func->displayMsg($type, $disp_msg);
                }
                ?>
                <form method="POST" action="login_p.php">
                    <div class="form-group">
                        <input class="form-control form-control-lg" name="adUser" id="username" type="text" placeholder="Enter Email">
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-lg" name="adPass" id="password" type="password" placeholder="Enter Password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block" name="loginbtn">Sign in</button>
                </form>
            </div>
            <div class="card-footer bg-white p-0  ">
                <div class="card-footer-item card-footer-item-bordered">
                    <a href="#" class="footer-link">Forgot Password</a>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end login page  -->
    <!-- ============================================================== -->
</body>
</html>
