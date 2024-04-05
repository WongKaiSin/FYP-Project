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
<script type="text/javascript">

function confirmation(){
	answer = confirm("Do you want to delete this staff?");
	return answer;
}
</script>
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
                                        <li class="breadcrumb-item"><a href="view_user.php" class="breadcrumb-link">View User List</a></li>
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
                    <!-- ============================================================== -->
                    <!-- data table  -->
                    <!-- ============================================================== -->
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">User List</h5>
                                <p>The list displays user information.</p>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered second" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Type</th>
                                                <th>Login Time</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            mysqli_select_db($db_conn,"bagel");
                                            $result = mysqli_query($db_conn, "SELECT * FROM member");	
                                            $count = mysqli_num_rows($result);
                                            while($row = mysqli_fetch_assoc($result))
                                            {
                                            
                                            ?>			

                                            <tr>
                                            <td><?php echo $row["MemberID"]; ?></td>
                                                <td><?php echo $row["MemberName"]; ?></td>
                                                <td><?php echo $row["MemberEmail"]; ?></td>
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
                                            
                                            ?>
                                        
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end data table  -->
                    <!-- ============================================================== -->
                    
                    <?php
if (isset($_REQUEST["del"])) 
{
	$MemberID = $_REQUEST["MemberID"]; 
	mysqli_query($db_conn, "DELETE FROM member WHERE MemberID = $MemberID");
	
}

?>