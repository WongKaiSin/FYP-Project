<?php
session_start();


include("lib/head.php"); 
include("lib/db.php");
include("lib/function.php");

$func = new Functions;

$adUser = $_SESSION["adUser"];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $currentPassword = $_POST["currentPassword"];
    $newPassword = $_POST["adPass"];
    $currentDateTime = date("Y-m-d H:i:s");

    // Retrieve current password from the database
    $sql = "SELECT adPass FROM `admin` WHERE adUser = '$adUser'";
    $result = $db_conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $storedPassword = $row["adPass"];

        // Check if current password matches the stored password
        if ($func->PassSign($adUser, $currentPassword) == $storedPassword) {
            // Perform update query
            $newPass = $func->PassSign($adUser, $newPassword);
            $sql = "UPDATE `admin` SET adPass = '$newPass', AdminModifyDate = '$currentDateTime' WHERE adUser = '$adUser'";
            $updateResult = $db_conn->query($sql);

            if ($updateResult) {
                // Update successful
                echo '<script type="text/javascript">
                            alert("Admin password updated successfully.");
                            window.location.href = "admin-profile.php";
                    </script>';
            } else {
                // Update failed
                echo "Error updating admin information: " . $db_conn->error;
            }
        } else {
            // Current password does not match
            echo '<script type="text/javascript">
                        alert("Current password is incorrect.");
                    </script>';
        }
    } else {
        // Error retrieving current password from the database
        echo "Error retrieving current password: " . $db_conn->error;
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>Reset Password</title>
</head>
<script>
    function checkPassword() {
        var password = document.getElementById("adPass").value;
        var confirm_password = document.getElementById("confirm_password").value;

        if (password !== confirm_password) {
            alert("Passwords do not match!");
            return false;
        }
        return true;
    }
</script>
<body>
    <div class="dashboard-main-wrapper">
        <?php
        include("lib/navbar.php");
        include("lib/sidebar.php");
        ?>
        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h2 class="pageheader-title">Reset Password</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">
                            <form action="" method="POST" onsubmit="return checkPassword();">
                            <div class="form-group">
                                <label for="currentPassword">Current Password</label>
                                <input id="currentPassword" type="password" class="form-control" name="currentPassword" required>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword">New Password</label>
                                <input id="adPass" type="password" class="form-control" name="adPass" required>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword">Confirm Password</label>
                                <input id="confirm_password" type="password" class="form-control" required>
                            </div>               
                                <button type="submit" name="save" class="btn btn-primary">Update</button>
                            </form>
               
                    </div>
                </div>
            </div>
</body>

</html>