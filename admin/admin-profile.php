<?php
session_start();

include("lib/head.php");
include("lib/db.php");

// Check if the user is logged in
if (!isset($_SESSION["adUser"])) {
    // Redirect to login page or handle unauthorized access
    // For example:
    header("Location: login.php");
    exit(); // Ensure script stops execution after redirection
}

// Get the admin username from the session
$adUser = $_SESSION["adUser"];


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updatebtn"])) {
    // Get form data
    $adEmail = $_POST["adEmail"];
    $adTel = $_POST["adTel"];
    $adAdd = $_POST["adAdd"];
    $adState = $_POST["adState"];
    $adCountry = $_POST["adCountry"];
    $adPostcode = $_POST["adPostcode"];
    $adCity = $_POST["adCity"];
    $currentDateTime = date("Y-m-d H:i:s");

    // Perform update query
    $sql = "UPDATE `admin` SET adEmail = '$adEmail', adTel = '$adTel', adAdd = '$adAdd', 
    adState = '$adState', adCountry = '$adCountry', adPostcode = '$adPostcode', adCity = '$adCity', 
    AdminModifyDate = '$currentDateTime' WHERE adUser = '$adUser'";
    $result = $db_conn->query($sql);

    if ($result) {
        // Update successful
        echo '<script type="text/javascript">
                        alert("Admin information updated successfully.");
                        window.location.href = "admin-profile.php";
            </script>';
    } else {
        // Update failed
        echo "Error updating admin information: " . $db_conn->error;
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>Admin Profile</title>
</head>

<body>
    <div class="dashboard-main-wrapper">
        <?php
        include("lib/navbar.php");
        include("lib/sidebar.php");
        
        // Perform database query to get admin data
        $sql = "SELECT * FROM `admin` WHERE adUser = '$adUser'";
        $result = $db_conn->query($sql);

        // Check if the query was successful
        if (!$result) {
            // Query failed, display error message
            echo "Error: " . $db_conn->error;
        } else {
            // Query was successful, fetch data
            while ($admindata = $result->fetch_assoc()) {
            $adminAddDate = new DateTime($admindata["AdminAddDate"]);
            $currentDate = new DateTime();
            $daysSinceAdd = $currentDate->diff($adminAddDate)->days;
        ?>

                                    

        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                <div class="card card-fluid">
                    <div class="card-img-top">
                        <img src="assets/images/profile_bg.jpg" alt="" class=" img-fluid">
                    </div>
                    <!-- .card-body -->
                    <div class="card-body text-center">
                        <!-- .user-avatar -->
                        <a href="user-profile.html" class="user-avatar user-avatar-floated user-avatar-xl">
                            <img src="assets/images/user.png" alt="User Avatar" class="rounded-circle user-avatar-xl">
                        </a>
                        <!-- /.user-avatar -->
                        <h3 class="card-title mb-2">
                            <?php echo $_SESSION["adName"]?>
                        </h3>
                        <h6 class="card-subtitle text-muted mb-3"><?php echo $_SESSION["adType"]?></h6>
                        <p class="card-subtitle text-muted mb-3">[ Joined for <?php echo $daysSinceAdd ?> Days ]</p>
                        <div class="text-center col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <form method="POST" action="admin-profile.php">
                                <table class="table">
                                    <tbody>
                                            <tr>
                                            <th>UserName</th>
                                            <td>
                                                <div class="form-group">
                                                    <input id="inputText3" type="text" class="form-control" name="adUser" value="<?php echo $admindata["adUser"] ?>" readonly>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Add Date</th>
                                            <td><input id="inputText3" type="text" class="form-control" name="AdminAddDate" value="<?php echo $admindata["AdminAddDate"] ?>" readonly></td>
                                        </tr>
                                        <tr>
                                            <th>Email <i class="m-r-10 mdi mdi-lead-pencil" style="color: #bebebe;"></i></th>
                                            <td><input id="inputText3" type="text" class="form-control" name="adEmail" value="<?php echo $admindata['adEmail']; ?>" required></td>
                                        </tr>
                                        <tr>
                                        <th>Phone Number  <i class="m-r-10 mdi mdi-lead-pencil" style="color: #bebebe;"></i></th>
                                        <td><input id="inputText3" type="text" class="form-control"  name="adTel" value="<?php echo $admindata['adTel']; ?>" required></td>
                                    </tr>
                                    <tr>
                                        <th>Address <i class="m-r-10 mdi mdi-lead-pencil" style="color: #bebebe;"></i></th>
                                        <td>
                                            <input id="inputText3" type="text" class="form-control" name="adAdd" value="<?php echo $admindata["adAdd"] ?>" required><br>
                                            <div class="form-inline">
                                                <input id="inputPostcode" type="text" class="form-control mr-2" name="adPostcode" value="<?php echo $admindata['adPostcode']; ?>" required>
                                                <input id="inputCity" type="text" class="form-control" name="adCity" value="<?php echo $admindata['adCity']; ?>" required>
                                            </div>
                                            <br>
                                            <div class="form-inline">
                                                <input id="inputState" type="text" class="form-control mr-2" name="adState" value="<?php echo $admindata['adState']; ?>" required>
                                                <input id="inputCounty" type="text" class="form-control" name="adCountry" value="<?php echo $admindata['adCountry']; ?>" required>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php            }
                                    }?>
                                <button type="submit" name="updatebtn" class="btn btn-primary float-right">Update</button>
                            </form>
                        </div>
                        <!-- /grid row -->
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</body>

</html>
