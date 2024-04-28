<?php
// Assuming you have already started the session
session_start();

// Check if MemberID is set in the session
if(isset($_SESSION['MemberID'])) {
    // Assuming you have already established a database connection
    include("lib/db.php");

    // Get the MemberID from the session
    $memberId = $_SESSION['MemberID'];

    // SQL query to select bookings for the logged-in member
    $sql = "SELECT booking.BookID, booking.People, booking.Date, booking.Time, booking.Approval, member.MemberName, member.MemberPhone  
            FROM booking 
            JOIN member ON booking.MemberID = member.MemberID 
            WHERE booking.MemberID = $memberId";

    // Execute the query
    $result = mysqli_query($db_conn, $sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("lib/head.php"); ?>
  <title>Booking | London Bagel Museum</title>
</head>
<body>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">
      <?php 
        include("lib/logo.php");
        include("lib/topmenu.php");
      ?>
    </div>
  </header><!-- End Header -->

  <main id="main">
    <!-- ======= Breadcrumbs ======= -->
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
    </div><!-- End Breadcrumbs -->

    <div class="container">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3">
          <?php include("lib/sidebar.php"); ?>
        </div>
        <!-- Main Content -->
        <div class="col-lg-9">
          <section id="bookinglist" class="bookinglist">
            <div class="container" data-aos="fade-up">
                <h3><span>Check Your Booking Details</span></h3>
                    <?php
                        if(isset($result)) {
                            if (mysqli_num_rows($result) > 0) {
                                $counter = 1;
                                echo "<table border='1'>";
                                echo "<tr><th>Booking No.</th><th>Name</th><th>Phone Number</th><th>People(pax)</th><th>Date</th><th>Time</th><th>Status</th><th>Action</th></tr>";
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $counter . "</td>";
                                    echo "<td>" . $row["MemberName"]. "</td>";
                                    echo "<td>" . $row["MemberPhone"]. "</td>";
                                    echo "<td>" . $row["People"]. "</td>";
                                    echo "<td>" . $row["Date"]. "</td>";
                                    echo "<td>" . $row["Time"]. "</td>";
                                    // Displaying approval status based on value
                                    echo "<td>";
                                    if ($row["Approval"] == 0) {
                                        echo "Not Approved";
                                    } else {
                                        echo "Successful Booking";
                                    }
                                    echo "</td>";
                                    // Cancel button
                                    echo "<td><a onclick='return confirm(\"Are you sure you want to cancel this booking?\")' href='forms/cancel_booking.php?id=" . $row["BookID"] . "'>Cancel</a></td>";
                                    echo "</tr>";
                                    $counter++;
                                }
                                echo "</table>";
                            } else {
                                echo "0 results";
                            }
                            mysqli_close($db_conn); // Close the connection
                        } else {
                            echo "Please login to view bookings.";
                        }
                    ?>
                </table>
            </div>
          </section>
        </div>
      </div>
    </div>
  </main><!-- End #main -->
  <?php include("lib/footer.php"); ?>
  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <div id="preloader"></div>
</body>
</html>
