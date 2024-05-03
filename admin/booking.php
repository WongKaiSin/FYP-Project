<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");

// Default to current date if no order date is provided
$start_date = '';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['start_date'])) {
    $start_date = $_GET['start_date'];
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['BookID']) && isset($_GET['new_status'])) {
    // Get form data
    $BookID = $_GET['BookID'];
    $new_status = $_GET['new_status'];

    // Perform update query
    $sql = "UPDATE `booking` SET Approval = '$new_status' WHERE BookID = '$BookID'";
    $result = $db_conn->query($sql);

    if ($result) {
        // Update successful
        echo '<script type="text/javascript">
                        alert("Booking status updated successfully.");
                        window.location.href = "booking.php";
            </script>';
    } else {
        // Update failed
        echo "Error updating booking information: " . $db_conn->error;
    }
}
?>


<!doctype html>
<html lang="en">
<style>

    .form{
        float: right;
        padding: 10px;
    }
    
</style>
<script>
    function resetDate() {        
        
        window.location.href = "booking.php";
    }
</script>
<head>

    <title>Booking List</title>
</head>
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
                            <h2 class="pageheader-title">Booking List</h2>

                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Booking List</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                                                
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                    <div class="card-header">
                    <form method="GET" action="booking.php" class="float-left">
                    <h5>Select Date 
                        <input type="date" class=" form-control" name="start_date" value="<?php echo $start_date; ?>">
                    </h5>
                    <button type="submit" class="btn btn-primary btn-xs">View Bookings</button>
                    <button type="button" class="btn btn-secondary btn-xs" onclick="resetDate()">Reset</button>
                    </form>
                    </div>

                        <div class="card-body">
                        <p>
                        <span class="mr-2"><span class="badge-dot badge-success"></span>Approved</span>
                        <span class="mr-2"> <span class="badge-dot badge-warning"></span>Waiting Approval</span>
                        <span class="mr-2"> <span class="badge-dot badge-dark"></span>Reject</span></p>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Time</th>
                                        <th scope="col">People(s)</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    mysqli_select_db($db_conn, "bagel");
                                    $sql = "SELECT * FROM booking ORDER BY `Date`, `Time`, `BookAddDate` DESC;";
                                    if ($start_date) {
                                        $sql = "SELECT * FROM booking WHERE `Date` = '$start_date' ORDER BY `Date`, `Time`, `BookAddDate` DESC;";
                                    }
                                    $query = $db_conn->query($sql);
                                    if ($query) {
                                        $current_date = '';
                                        while ($row = $query->fetch_assoc()) {
                                            $endtime = $row['Time'] + 1;
                                            if ($current_date != $row['Date']) {
                                                echo '<tr style="background-color:whitesmoke ;"><td colspan="7"><strong>' . $row['Date'] . '</strong></td></tr>';
                                                $current_date = $row['Date'];
                                            }
                                            if($row['Approval']==0){
                                                $status='class="badge-dot  badge-warning"';
                                            }
                                            else if($row['Approval']==1){
                                                $status='class="badge-dot  badge-success"';
                                            }
                                            else if($row['Approval']==2){
                                                $status='class="badge-dot  badge-dark"';
                                            }
                                    ?>
                                            <tr>
                                                <th scope="row"><span <?php echo $status; ?>></span></th>
                                                <td><?php echo $row['Name']; ?></td>
                                                <td><?php echo $row['Phone']; ?></td>
                                                <td><?php echo $row['Email']; ?></td>
                                                <td><?php echo $row['Time']; ?> - <?php echo $endtime; ?></td>
                                                <td><?php echo $row['People']; ?></td>
                                                <td>
                                                    <?php if ($row['Approval'] == 0) { ?>
                                                        <a href="booking.php?BookID=<?php echo $row['BookID']; ?>&new_status=1"><i class="m-r-10 fas fa-calendar-check"></i></a>
                                                        <a href="booking.php?BookID=<?php echo $row['BookID']; ?>&new_status=2"><i class="m-r-10 fas fa-calendar-times"></i></a>
                                                    <?php } ?>
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
                <!-- ============================================================== -->
                <!-- end hoverable table -->
                <!-- ============================================================== -->


                <script>
                    function approveBooking(bookingId) {
                        console.log('Booking Approved:', bookingId);
                    }

                    function rejectBooking(bookingId) {
                        console.log('Booking Rejected:', bookingId);
                    }
                </script>
