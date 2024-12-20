<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");

if (isset($_GET["del"])) {
    // Retrieve adID from request parameters
    $adID = $_GET['adID']; 
    $adtype = $_SESSION["adType"];


    if ($adtype == "SuperAdmin") {
        
        $sql_delete = "UPDATE `admin` SET adStatus = 0 WHERE adID = '$adID'";
        if ($db_conn->query($sql_delete) === TRUE) {
            // Redirect back to the admin page
            header("Location: admin-view.php");
            exit();
        } 
        else {
            // Handle errors, if any
            echo "Error deleting admin: " . $db_conn->error;
        }
    } 
    else {
        // Display error message - Only superadmins can delete admins
        echo "<script>alert('Only superadmins can delete other admins.');</script>";
    }
        
    }
?>
<!doctype html>
<html lang="en">

<head>
    <title>View Admin List</title>
</head>
<style>
    .top-search-bar {
        float: left;
        padding: 10px;
    }

    .align-items-center{
        padding: 10px;        
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    .media-body {
        margin-left: 20px; 
        padding: 20px;
    }

    .add-btn {
            float: right;
            margin-bottom: 10px;
        }
    
</style>
<script type="text/javascript">

function confirmation(){
    answer = confirm("Do you want to delete this admin?");
    return answer;
}


function search() {
  // Declare variables
  var input, filter, ul, li, div, h4, i, txtValue;
  input = document.getElementById('myInput');
  filter = input.value.toUpperCase();
  ul = document.getElementById("myUL");
  li = ul.getElementsByTagName('li');

  // Loop through all list items, and hide those who don't match the search query
  for (i = 0; i < li.length; i++) {
    div = li[i].getElementsByClassName("media-body")[0];
    h4 = div.getElementsByTagName("h4")[0];
    txtValue = h4.textContent || h4.innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      li[i].style.display = "";
    } else {
      li[i].style.display = "none";
    }
  }
}

function addAdmin(){
    
    var Type = "<?php echo $_SESSION["adType"]; ?>";
    
    if (Type == 'SuperAdmin') {
            window.location.href = "admin-add.php";
        }
    else{
        alert('Only superadmins can add other admins.');
            return false; 
    }
    
        
}



</script>

<body>
    <!-- main wrapper -->
    <div class="dashboard-main-wrapper">
        <?php 
            include("lib/navbar.php");
            include("lib/sidebar.php");
        ?>

        <!-- wrapper  -->
        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                <!-- pageheader -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h2 class="pageheader-title">View Admin List</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">View Admin List</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end pageheader -->

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                        <div class="card-header">
                            <div id="custom-search" class="top-search-bar">
                                    <input type="text" class="form-control" id="myInput" onkeyup="search()" placeholder="Search..">
                            </div>
                            <button onclick="addAdmin()" class="btn btn-primary add-btn">Add Admin</button>
                        </div>
                            <div class="card-body">
                                <?php
                                $sql = "SELECT * FROM admin WHERE adStatus=1";
                                $query = $db_conn->query($sql);
                                if ($query) {
                                    echo '<ul id="myUL">'; 
                                    while ($row = $query->fetch_assoc()) {
                                        $defaultImage = "../upload/admin/default-profile.png";
                                        $profileImage = empty($row['adLogo']) ? $defaultImage : "../upload/admin/" . $row['adLogo'];
                                ?>

                                        <li>
                                            <div class="media align-items-center">
                                            <div id="display-image">
                                                <img src="<?php echo $profileImage; ?>" class="rounded-circle user-avatar-xl">
                                            </div>

                                                <div class="media-body">
                                                    <h4 class="card-title mb-2 text-truncate"><?php echo $row["adName"] ?>
                                                    
                                                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse_<?php echo $row['adID']; ?>" aria-expanded="false" aria-controls="collapse_<?php echo $row['adID']; ?>">
                                                            <span class="fas fa-angle-down mr-3"></span>
                                                        </button></h4>
                                                    <p class="card-subtitle text-muted"><?php echo $row["adType"] ?></p>
                                                    <div id="collapse_<?php echo $row['adID']; ?>" class="collapse" aria-labelledby="heading_<?php echo $row['adID']; ?>" data-parent="#myUL">
                                                        <div class="card-body">
                                                            <table class="table">
                                                            <tbody>
                                                            <tr>
                                                                        <th>ID</th>
                                                                            <td><?php echo $row["adID"] ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Email</th>
                                                                            <td><?php echo $row["adEmail"] ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Phone Number</th>
                                                                            <td><?php echo $row["adTel"] ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Address</th>
                                                                            <td><?php echo $row["adAdd"] ?>, 
                                                                            <?php echo $row["adPostcode"] ?>, <?php echo $row["adCity"] ?>, <?php echo $row["adState"] ?>, <?php echo $row["adCountry"] ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Add Date</th>
                                                                            <td><?php echo $row["AdminAddDate"] ?></td>
                                                                        </tr>
                                                                </tbody>
                                                            </table>

                                                            <div class="col-sm-12 pl-0 text-right">
                                                                <p style="padding: 20px;">      
                                                                <a class="btn btn-secondary" href="admin-view.php?del=1&adID=<?php echo $row["adID"]; ?>" onclick="return confirmation();">Delete</a>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                <?php
                                    }
                                    echo '</ul>'; // End the unordered list
                                } else {
                                    echo "Error fetching records: " . $db_conn->error;
                                }
                                ?>
                                
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>


