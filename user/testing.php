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
    $sql = "SELECT booking.People, booking.Date, booking.Time, booking.Approval, member.MemberName, member.MemberPhone  
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
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
      color: #333;
    }
    .container {
      margin-top: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      background-color: #fff;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }
    th {
      background-color: #007bff;
      color: #fff;
      text-transform: uppercase;
    }
    tr:nth-child(even) {
      background-color: #f2f2f2;
    }
    tr:hover {
      background-color: #ddd;
    }
    h2 {
      margin-bottom: 20px;
    }
  </style>
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
      <section id="profile" class="profile">
        <div class="container" data-aos="fade-up">
            <h2>Check Your Booking Details</h2>
            <?php
                if(isset($result)) {
                    if (mysqli_num_rows($result) > 0) {
                        echo "<table>";
                        echo "<tr><th>#</th><th>People</th><th>Date</th><th>Time</th><th>Status</th><th>Member Name</th><th>Member Phone</th></tr>";
                        $counter = 1;
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $counter . "</td>";
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
                            echo "<td>" . $row["MemberName"]. "</td>"; // Changed to "MemberName"
                            echo "<td>" . $row["MemberPhone"]. "</td>";
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
        </div>
      </section>
    </div>
  </main><!-- End #main -->
  <?php include("lib/footer.php"); ?>
  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <div id="preloader"></div>
</body>
</html>
