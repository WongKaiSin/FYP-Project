<?php
session_start();
include("lib/db.php");

if(isset($_SESSION['MemberID'])) {
    $memberId = $_SESSION['MemberID'];
    $sql = "SELECT booking.BookID, booking.People, booking.Date, booking.Time, booking.Approval, member.MemberName, member.MemberPhone  
            FROM booking 
            JOIN member ON booking.MemberID = member.MemberID 
            WHERE booking.MemberID = ?";
    $stmt = mysqli_prepare($db_conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $memberId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("lib/head.php"); ?>
  <title>Booking | London Bagel Museum</title>
</head>
<body>
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">
      <?php 
        include("lib/logo.php");
        include("lib/topmenu.php");
      ?>
    </div>
  </header>

  <main id="main">
    <div class="breadcrumbs">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center">
          <h2>Booking Details</h2>
          <ol>
            <li><a href="index.php">Home</a></li>
            <li>Booking Details</li>
          </ol>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-lg-3">
          <?php include("lib/sidebar.php"); ?>
        </div>
        <div class="col-lg-9">
          <section id="bookinglist" class="bookinglist">
            <div class="container" data-aos="fade-up">
                <h3><span>Check Your Booking Details</span></h3>
                <div class="card-body">
                    <p>
                    <span class="badge-dot badge-success"></span>Approved</span>
                    <span class="badge-dot badge-warning"></span>Waiting Approval</span>
                    <span class="badge-dot badge-dark"></span>Rejected</span>
                    </p>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Phone</th>
                                <th scope="col">People(s)</th>
                                <th scope="col">Date</th>
                                <th scope="col">Time</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($result)) {
                                    if (mysqli_num_rows($result) > 0) {
                                        $counter = 1;
                                        while($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>";
                                            if ($row["Approval"] == 0) {
                                                echo "<span class='badge-dot badge-warning'></span>";
                                            } elseif ($row["Approval"] == 1) {
                                                echo "<span class='badge-dot badge-success'></span>";
                                            } else {
                                                echo "<span class='badge-dot badge-dark'></span>";
                                            }
                                            echo "</td>";
                                            echo "<td>" . $row["MemberName"]. "</td>";
                                            echo "<td>" . $row["MemberPhone"]. "</td>";
                                            echo "<td>" . $row["People"]. "</td>";
                                            echo "<td>" . $row["Date"]. "</td>";
                                            echo "<td>" . $row["Time"]. "</td>";
                                            echo "<td><a onclick='return confirm(\"Are you sure you want to cancel this booking?\")' href='forms/cancel_booking.php?id=" . $row["BookID"] . "'>Cancel</a></td>";
                                            echo "</tr>";
                                            $counter++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='8'>No bookings found.</td></tr>";
                                    }
                                    mysqli_close($db_conn);
                                } else {
                                    echo "<tr><td colspan='8'>Please login to view bookings.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </main>
  <?php include("lib/footer.php"); ?>
  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <div id="preloader"></div>
</body>
</html>
