<?php
include("connect.php");
session_start();

// Check if the login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["loginbtn"])) {
    $adUser = $_POST['adUser'];
    $adPass = $_POST['adPass'];

    // Prepare the SQL statement to fetch the user from the database
    $sql = "SELECT * FROM admin WHERE adUser = ? AND adPass = ?";
    
    // Prepare and execute the SQL statement
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $adUser, $adPass);
    $stmt->execute();
    
    // Retrieve the result
    $result = $stmt->get_result();
    
    // Check if the user exists
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Set session variables
        $_SESSION["STAFF_ID"] = $row["STAFF_ID"];
        $_SESSION["STAFF_NAME"] = $row["STAFF_NAME"];
        // Add more session variables as needed
        ?>  	
                
            
                    <script type="text/javascript">
                        alert("<?php echo ' LOGIN SUCCESSFUL' ?>");
                    </script>
                
                <?php
        // Redirect to the home page or desired location
        header("Location: index.php");
        exit();
    } 
    else {
        $login_error = "Invalid user ID or password.";
        ?>  	
                
            
                    <script type="text/javascript">
                        alert("<?php echo ' WRONG PASSWORD OR USERNAME' ?>");
                    </script>
                
                <?php
    }
}
?>
<!doctype html>
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
            <div class="card-header text-center"><a href="../index.php"><img class="logo-img" src="assets/images/logo.jpg" alt="logo" height=155px weight=35px></a><span class="splash-description">Please enter your information.</span></div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <input class="form-control form-control-lg" name="adUser" id="username" type="text" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-lg" name="adPass" id="password" type="password" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block" name="loginbtn">Sign in</button>
                </form>
            </div>
            <div class="card-footer bg-white p-0  ">
                <div class="card-footer-item card-footer-item-bordered">
                    <a href="#" class="footer-link">Create An Account</a></div>
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
