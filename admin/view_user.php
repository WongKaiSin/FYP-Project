<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");

?>
<!doctype html>
<html lang="en">

<head>
  
    <title>View User List</title>
</head>
<style>

    .top-search-bar {

        float: right;
        padding: 10px;
}

.card-header{
        position:sticky;
    }

    .card-body {
    height: 400px; 
    overflow-y: auto; 
}
</style>
<script type="text/javascript">

function confirmation(){
	answer = confirm("Do you want to delete this user?");
	return answer;
}

function search() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById('myInput');
            filter = input.value.toUpperCase();
            table = document.getElementById("example");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1]; // Change index to match the column containing name
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
</script>
<?php
if (isset($_REQUEST["del"])) 
{
	$MemberID = $_REQUEST["MemberID"]; 
	$sql = "DELETE FROM member WHERE MemberID = $MemberID";
    $query = $db_conn->query($sql);
	if ($query) {
        header("Location: view_user.php");
        exit();
    } else {
        echo "Error deleting record: " . $db_conn->error;
    }
}

?>
<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
        <?php 
            include("lib/navbar.php");
            include("lib/sidebar.php");
        ?>


        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                <!-- ============================================================== -->
                <!-- pageheader -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h2 class="pageheader-title">View User List</h2>

                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">View User List</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end pageheader -->
                <!-- ============================================================== -->

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                        <div class="card-header">
                            <div id="custom-search" class="top-search-bar">
                                    <input type="text" class="form-control" id="myInput" onkeyup="search()" placeholder="Search..">
                            </div>
                        </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered second" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Type</th>
                                                <th>Login Time</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            mysqli_select_db($db_conn, "bagel");
                                            $sql = "SELECT * FROM member";
                                            $query = $db_conn->query($sql);
                                            if ($query) {
                                                while ($row = $query->fetch_assoc()) {
                                            ?>
                                                    <tr>
                                                        <td><?php echo $row["MemberID"]; ?></td>
                                                        <td><?php echo $row["MemberName"]; ?></td>
                                                        <td><?php echo $row["MemberEmail"]; ?></td>
                                                        <td><?php echo $row["MemberPhone"]; ?></td>
                                                        <td><?php echo $row["MemberType"]; ?></td>
                                                        <td><?php echo $row["MemberLoginTime"]; ?></td>
                                                        <td>
                                                            <span style="font-style: italic; text-decoration: underline;">
                                                                <a href="view_user.php?del=1&MemberID=<?php echo $row['MemberID']; ?>" onclick="return confirmation();">Delete</a>
                                                            </span>
                                                        </td>
                                                    </tr>
                                            <?php
                                                }
                                            } else {
                                                echo "Error fetching records: " . $db_conn->error;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End User List -->
            </div>
        </div>
    </div>
</body>

</html>