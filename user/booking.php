<?php
session_start();
include("lib/db.php");
if (!isset($_SESSION['MemberEmail'])) {
  header("Location: registration.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("lib/head.php"); ?>
  <title>Reservations | London Bagel Museum</title>
  <style>
    .reservation-form {
      padding: 30px;
      background-color: #f9f9f9;
      border-radius: 10px;
    }

    .reservation-form h3 {
      margin: 0;
      font-size: 30px;
      font-weight: bold;
      color: #ec2727;
    }

    .reservation-form p {
      color: #777;
    }

    .form-control {
      border-radius: 5px;
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
          <h2>Reservations</h2>
          <ol>
            <li><a href="index.php">Home</a></li>
            <li>Reservations</li>
          </ol>
        </div>

      </div>
    </div><!-- End Breadcrumbs -->

    <!-- ======= Book A Table Section ======= -->
    <section id="book-a-table" class="book-a-table">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Book A Table</h2>
          <p>Book <span>Your Table</span> With Us</p>
        </div>

        <div class="row g-0">

          <div class="col-lg-4 reservation-img" style="background-image: url(assets/img/booking.jpg);" data-aos="zoom-out" data-aos-delay="200"></div>

          <div class="col-lg-8 d-flex align-items-center reservation-form-bg">
            <div class="reservation-form">
              <h3>Reservation Details</h3>
              <p class="fst-italic"> We offer <span>five tables</span> available for reservation every hour, each comfortably seating up to <span>4 people</span>. 
            Our maximum capacity per hour is <span>20 individuals</span>. If the number of people exceeds this limit, unfortunately, reservations for that time slot cannot be accommodated.
              </p>
              <form action="forms/book-a-table.php" method="post" role="form" class="php-email-form" data-aos="fade-up" data-aos-delay="100">
                <div class="row">
                  <div class="col-lg-6">
                    <input type="date" name="Date" class="form-control" id="Date" placeholder="Date" data-rule="minlen:4" data-msg="Please enter at least 4 chars">
                    <div class="validate"></div>
                  </div>

                  <div class="col-lg-6">
                    <select class="form-control" name="Time" id="Time" data-rule="minlen:1" data-msg="Please select a time">
                      <option value="">Time</option>
                      <option value="8AM - 9AM">8AM - 9AM</option>
                      <option value="9AM - 10AM">9AM - 10AM</option>
                      <option value="10AM - 11AM">10AM - 11AM</option>
                      <option value="11AM - 12PM">11AM - 12PM</option>
                      <option value="12PM - 1PM">12PM - 1PM</option>
                      <option value="1PM - 2PM">1PM - 2PM</option>
                      <option value="2PM - 3PM">2PM - 3PM</option>
                      <option value="3PM - 4PM">3PM - 4PM</option>
                      <option value="4PM - 5PM">4PM - 5PM</option>
                      <option value="5PM - 6PM">5PM - 6PM</option>
                    </select>
                    <div class="validate"></div>
                  </div>

                  <div class="col-lg-6">
                    <input type="number" class="form-control" name="People" id="People" placeholder="# of people" data-rule="minlen:1" data-msg="Please enter at least 1 chars" min="1" max="20">
                    <div class="validate"></div>
                  </div>

                  <div class="col-lg-6">
                    <input type="text" class="form-control" id="Table" placeholder="Table no" readonly>
                    <div class="validate"></div>
                  </div>
                </div>

                <div class="mb-3">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your booking request was sent. We will call back or send an Email to confirm your reservation. Thank you!</div>
                </div>
                <div class="text-center"><button type="submit" name="BtnBooking" class="btn-book-table">Book a Table</button></div>
              </form>
            </div>
          </div><!-- End Reservation Form -->

        </div>

      </div>
    </section><!-- End Book A Table Section -->

  </main><!-- End #main -->
  <?php include("lib/footer.php"); ?>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <script>
    document.getElementById('People').addEventListener('input', function() {
      var People = this.value;
      var tablesNeeded = Math.ceil(People / 4); // Calculate number of tables needed
      tablesNeeded = Math.min(tablesNeeded, 5); // Limit maximum tables to 5
      document.getElementById('Table').value = tablesNeeded;
    });

    document.addEventListener('DOMContentLoaded', function() {
      var dateInput = document.getElementById('Date');
      var today = new Date();
      var tomorrow = new Date(today);
      tomorrow.setDate(tomorrow.getDate() + 1);

      var maxDate = new Date(today);
      maxDate.setMonth(today.getMonth() + 1);

      dateInput.min = tomorrow.toISOString().split('T')[0];
      dateInput.max = maxDate.toISOString().split('T')[0];
    });
  </script>

</body>
</html>
