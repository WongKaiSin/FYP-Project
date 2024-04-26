<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("lib/head.php"); ?>
  <title>Reservations | London Bagel Museum</title>

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
            <form action="forms/book-a-table.php" method="post" role="form" class="php-email-form" data-aos="fade-up" data-aos-delay="100">
            <p class="fst-italic"><span><b>Information:</b></span> We offer <span>five tables</span> available for reservation every hour, each comfortably seating up to <span>4 people</span>. 
            Our maximum capacity per hour is <span>20 individuals</span>. If the number of people exceeds this limit, unfortunately, reservations for that time slot cannot be accommodated.
            </p>
              <div class="row gy-4">
                <div class="col-lg-4 col-md-6">
                  <input type="date" name="Date" class="form-control" id="Date" placeholder="Date" data-rule="minlen:4" data-msg="Please enter at least 4 chars">
                  <div class="validate"></div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <select class="form-control" name="Time" id="Time" data-rule="minlen:1" data-msg="Please select a time">
                        <option value="">Time</option>
                        <option value="1">8AM - 9AM</option>
                        <option value="2">9AM - 10AM</option>
                        <option value="3">10AM - 11AM</option>
                        <option value="4">11AM - 12PM</option>
                        <option value="5">12PM - 1PM</option>
                        <option value="6">1PM - 2PM</option>
                        <option value="7">2PM - 3PM</option>
                        <option value="8">3PM - 4PM</option>
                        <option value="9">4PM - 5PM</option>
                        <option value="10">5PM - 6PM</option>
                    </select>
                    <div class="validate"></div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                  <input type="number" class="form-control" name="People" id="People" placeholder="# of people" data-rule="minlen:1" data-msg="Please enter at least 1 chars" min="1" max="20">
                  <div class="validate"></div>
                </div>
                <div class="col-lg-4 col-md-6">
                  <input type="text" class="form-control" id="Table" placeholder="Table no" readonly>
                  <div class="validate"></div>
                </div>
              </div>
              
              <div class="mb-3">
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Your booking request was sent. We will call back or send an Email to confirm your reservation. Thank you!</div>
              </div>
              <div class="text-center"><button type="submit" name="BtnBooking">Book a Table</button></div>
            </form>
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
  </script>
</body>
</html>