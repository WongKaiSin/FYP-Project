<?php
session_start();
include("lib/db.php");
if (!isset($_SESSION['MemberEmail'])) {
  // If the user is not logged in, redirect to the registration page
  $link = "registration.php";
} else {
  // If the user is logged in, direct them to the menu page
  $link = "menu.php";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("lib/head.php"); ?>
  <title>London Bagel Museum</title>

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

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="hero d-flex align-items-center section-bg">
    <div class="container">
      <div class="row justify-content-between gy-5">
        <div class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center align-items-center align-items-lg-start text-center text-lg-start">
          <h2 data-aos="fade-up">"We want to eat a lot of really delicious and warm bagel in 365 days!"</h2>
          <p data-aos="fade-up" data-aos-delay="100">Fresh Bagels just baked, warm and healthy homemade Soup, completely dramatic Coffee. There are friendly and witty staff who always having fun small talk. Welcome to London Bagel Museum!</p>
          <div class="d-flex" data-aos="fade-up" data-aos-delay="200">
            <a href="booking.php" class="btn-book-a-table">Book a Table</a>
            <a href="<?php echo $link; ?>" class="btn-book-a-table">Start Order</a>
          </div>
        </div>
        <div class="col-lg-5 order-1 order-lg-2 text-center text-lg-start">
          <img src="assets/img/logo.png" class="img-fluid" alt="" data-aos="zoom-out" data-aos-delay="300">
        </div>
      </div>
    </div>
  </section><!-- End Hero Section -->

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>About Us</h2>
          <p>Learn More <span>About Us</span></p>
        </div>

        <div class="row gy-4">
          <div class="col-lg-7 position-relative about-img" style="background-image: url(assets/img/about.jpg) ;" data-aos="fade-up" data-aos-delay="150">
            <div class="call-us position-absolute">
              <h4>Book a Table</h4>
              <p>+60 11-319 1008</p>
            </div>
          </div>
          <div class="col-lg-5 d-flex align-items-end" data-aos="fade-up" data-aos-delay="300">
            <div class="content ps-0 ps-lg-5">
              <p class="fst-italic">
              A typical New York style bagel(A common bread-like bagel with a matte texture due to excessive fermentation) and the bagel with evenly mixed cream cheese are not our type of bagel.
              </p>
              <p class="fst-italic">
              We like homemade-style bagels that are a little bumpy and ugly, but full of moisture and chewy. I think the bagels we ate every day at Brick Lane in London were like that.
              </p>
              <p class="fst-italic">
              We spent a lot of time testing to make such a bagel. We were disappointed and had a hard time, because we were tired of repeating recipe revisions. In order to make a bagel with a texture and taste that has never been eaten anywhere else, we repeated testing the bagel with our bakers, and we started to reach the desired result.
              </p>
              <p>
              That's how our 'London Bagel Museum' unique bagel was born! YEAHHHH!
              </p>

              <div class="position-relative mt-4">
                <img src="assets/img/about-2.jpg" class="img-fluid" alt="">
                <a href="https://youtu.be/3ysfWf2t8Po?si=vqRvGfc0S51Adhrs" class="glightbox play-btn"></a>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section><!-- End About Section -->

    <!-- ======= Why Us Section ======= -->
    <section id="why-us" class="why-us section-bg">
      <div class="container" data-aos="fade-up">

        <div class="row gy-4">

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="why-box">
              <h3>Why London Among So Many Cities?</h3>
              <p>
              I think my experience traveling in London is the biggest reason why the bagel shop is named after London. 
              When I walked around London, visited the National Portrait Gallery, or looked around the British Museum, I ate bagels. 
              These experiences are not special, but strangely, they became the most memorable scenes. Actually, the bagel I ate every time was more memorable than the artwork. 
              For these reasons, when naming the bagel shop, of course, "London" came to mind first.
              </p>
              <div class="text-center">
                <a href="#" class="more-btn">Learn More <i class="bx bx-chevron-right"></i></a>
              </div>
            </div>
          </div><!-- End Why Box -->

          <div class="col-lg-8 d-flex align-items-center">
            <div class="row gy-4">

              <div class="col-xl-4" data-aos="fade-up" data-aos-delay="200">
                <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                  <i class="bi bi-clipboard-data"></i>
                  <h4>Reservation</h4>
                  <p>The first service we provide</p>
                </div>
              </div><!-- End Icon Box -->

              <div class="col-xl-4" data-aos="fade-up" data-aos-delay="300">
                <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                  <i class="bi bi-gem"></i>
                  <h4>Delivery</h4>
                  <p>The second service we provide</p>
                </div>
              </div><!-- End Icon Box -->

              <div class="col-xl-4" data-aos="fade-up" data-aos-delay="400">
                <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                  <i class="bi bi-inboxes"></i>
                  <h4>Pick-Up</h4>
                  <p>The third service we provide</p>
                </div>
              </div><!-- End Icon Box -->

            </div>
          </div>

        </div>

      </div>
    </section><!-- End Why Us Section -->

    <!-- ======= Menu Section ======= -->
    <section id="menu" class="menu">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>London Bagel Museum Menu</h2>
          <p>Check Our <span>Recommendation</span></p>
        </div>

        <ul class="nav nav-tabs d-flex justify-content-center" data-aos="fade-up" data-aos-delay="200">

          <li class="nav-item">
            <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#menu-starters">
              <h4>Bagels</h4>
            </a>
          </li><!-- End tab nav item -->

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu-breakfast">
              <h4>Soup</h4>
            </a>
          </li><!-- End tab nav item -->

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu-lunch">
              <h4>Drinks</h4>
            </a>
          </li><!-- End tab nav item -->

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu-dinner">
              <h4>Cream Cheese</h4>
            </a>
          </li><!-- End tab nav item -->

        </ul>

        <div class="tab-content" data-aos="fade-up" data-aos-delay="300">

          <div class="tab-pane fade active show" id="menu-starters">

            <div class="tab-header text-center">
              <p>Recommendation</p>
              <h3>Bagels</h3>
            </div>

            <div class="row gy-5">

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/bagel/spring_onion_pretzel.jpg" class="menu-img img-fluid" alt="">
                <h4>Spring Onion Pretzel Bagel</h4>
                <p class="ingredients">
                flour, water, yeast, spring onions, salt
                </p>
                <p class="price">
                  RM 8.50
                </p>
              </div><!-- Menu Item -->

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/bagel/potato_cheese.jpg" class="menu-img img-fluid" alt="">
                <h4>Potato Cheese Bagel</h4>
                <p class="ingredients">
                flour, water, yeast, potato, cheese, sugar, salt
                </p>
                <p class="price">
                  RM 5.50
                </p>
              </div><!-- Menu Item -->

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/bagel/butter_salt_pretzel.jpg" class="menu-img img-fluid" alt="">
                <h4>Butter Salt Pretzel Bagel</h4>
                <p class="ingredients">
                flour, water, yeast, butter, salt
                </p>
                <p class="price">
                  RM 5.90
                </p>
              </div><!-- Menu Item -->

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/bagel/brick_lane.jpg" class="menu-img img-fluid" alt="">
                <h4>Brick Lane Sandwich</h4>
                <p class="ingredients">
                flour, water, yeast, cream
                </p>
                <p class="price">
                  RM 6.80
                </p>
              </div><!-- Menu Item -->

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/bagel/jambong_butter.jpg" class="menu-img img-fluid" alt="">
                <h4>Jambong Butter Sandwich</h4>
                <p class="ingredients">
                bagel, jambon (ham), butter
                </p>
                <p class="price">
                  RM 8.50
                </p>
              </div><!-- Menu Item -->

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/bagel/bacon_potato.jpg" class="menu-img img-fluid" alt="">
                <h4>Bacon Potato Sandwich</h4>
                <p class="ingredients">
                bagel, bacon, potato
                </p>
                <p class="price">
                  RM 14.80
                </p>
              </div><!-- Menu Item -->

            </div>
          </div><!-- End Starter Menu Content -->

          <div class="tab-pane fade" id="menu-breakfast">

            <div class="tab-header text-center">
              <p>Recommendation</p>
              <h3>Soup</h3>
            </div>

            <div class="row gy-5">

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/soup/tomato.jpg" class="menu-img img-fluid" alt="">
                <h4>Tomato Rose Soup</h4>
                <p class="ingredients">
                ripe tomatoes, onion, garlic, vegetable broth, cream, rose water
                </p>
                <p class="price">
                  RM 10.50
                </p>
              </div><!-- Menu Item -->

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/soup/mushroom.jpg" class="menu-img img-fluid" alt="">
                <h4>Mushroom Truffle</h4>
                <p class="ingredients">
                mixed mushrooms, onion, garlic, vegetable broth, cream, truffle oil, salt, pepper, fresh parsley
                </p>
                <p class="price">
                  RM 12.80
                </p>
              </div><!-- Menu Item -->

            </div>
          </div><!-- End Breakfast Menu Content -->

          <div class="tab-pane fade" id="menu-lunch">

            <div class="tab-header text-center">
              <p>Recommendation</p>
              <h3>Drinks</h3>
            </div>

            <div class="row gy-5">

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/drink/1.jpeg" class="menu-img img-fluid" alt="">
                <h4>Americcano</h4>
                <p class="ingredients">
                water and espresso
                </p>
                <p class="price">
                  RM 5.00
                </p>
              </div><!-- Menu Item -->

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/drink/Cappuccino.jpg" class="menu-img img-fluid" alt="">
                <h4>Cappuccino</h4>
                <p class="ingredients">
                espresso, steamed milk, steamed milk foam
                </p>
                <p class="price">
                  RM 6.00
                </p>
              </div><!-- Menu Item -->

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/drink/Espresso.jpg" class="menu-img img-fluid" alt="">
                <h4>Espresso</h4>
                <p class="ingredients">
                espresso, water
                </p>
                <p class="price">
                  RM 5.00
                </p>
              </div><!-- Menu Item -->

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/drink/earlgrey.jpg" class="menu-img img-fluid" alt="">
                <h4>Earl Grey Tea</h4>
                <p class="ingredients">
                  black tea, bergamot
                </p>
                <p class="price">
                  RM 6.50
                </p>
              </div><!-- Menu Item -->

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/drink/rooibos.jpg" class="menu-img img-fluid" alt="">
                <h4>Rooibos Tea</h4>
                <p class="ingredients">
                water, rooibos leaves
                </p>
                <p class="price">
                  RM6.50
                </p>
              </div><!-- Menu Item -->

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/drink/lemonade.jpg" class="menu-img img-fluid" alt="">
                <h4>Fresh Lemonade</h4>
                <p class="ingredients">
                lemons, water, sugar
                </p>
                <p class="price">
                  RM 7.50
                </p>
              </div><!-- Menu Item -->

            </div>
          </div><!-- End Lunch Menu Content -->

          <div class="tab-pane fade" id="menu-dinner">

            <div class="tab-header text-center">
              <p>Recommendation</p>
              <h3>Cream Cheese</h3>
            </div>

            <div class="row gy-5">

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/cream/basil_pesto.jpg" class="menu-img img-fluid" alt="">
                <h4>Basil Pesto Cream Cheese</h4>
                <p class="ingredients">
                cream cheese, basil pesto
                </p>
                <p class="price">
                  RM 3.80
                </p>
              </div><!-- Menu Item -->

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/cream/chives_garlic.jpg" class="menu-img img-fluid" alt=""></a>
                <h4>Garlic Spring Onion Cream Cheese</h4>
                <p class="ingredients">
                cream cheese, garlic, spring onions
                </p>
                <p class="price">
                  RM 3.80
                </p>
              </div><!-- Menu Item -->

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/cream/lemon_curd.jpg" class="menu-img img-fluid" alt=""></a>
                <h4>Lemon Curd Cream Cheese</h4>
                <p class="ingredients">
                cream cheese, lemon curd
                </p>
                <p class="price">
                  RM 3.80
                </p>
              </div><!-- Menu Item -->

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/cream/maple_pecan.jpg" class="menu-img img-fluid" alt=""></a>
                <h4>Maple Pecan Cream Cheese</h4>
                <p class="ingredients">
                cream cheese, maple syrup, pecans
                </p>
                <p class="price">
                  RM 3.80
                </p>
              </div><!-- Menu Item -->

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/cream/raspberry.jpg" class="menu-img img-fluid" alt=""></a>
                <h4>Raspberry Cream Cheese</h4>
                <p class="ingredients">
                cream cheese, raspberries
                </p>
                <p class="price">
                  RM 3.80
                </p>
              </div><!-- Menu Item -->

              <div class="col-lg-4 menu-item">
                <img src="assets/img/menu/cream/salmon_capers.jpg" class="menu-img img-fluid" alt=""></a>
                <h4>Salmon Capers Cream Cheese</h4>
                <p class="ingredients">
                cream cheese, smoked salmon, capers
                </p>
                <p class="price">
                  RM 4.30
                </p>
              </div><!-- Menu Item -->

            </div>
          </div><!-- End Dinner Menu Content -->

        </div>

      </div>
    </section><!-- End Menu Section -->

    <!-- ======= Testimonials Section ======= -->
    <section id="testimonials" class="testimonials section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Tips</h2>
          <p>How to eat <span>the most delicious bagel in the world</span></p>
        </div>

        <div class="slides-1 swiper" data-aos="fade-up" data-aos-delay="100">
          <div class="swiper-wrapper">

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        Just eat a really freshly baked hot Bagel.
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>Recommended by</h3>
                      <h4>London Bagel Museum</h4>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/tips.jpg" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        Spread butter and jam on a Plain Bagel(You can sprinkle with salt if you like)
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>Recommended by</h3>
                      <h4>London Bagel Museum</h4>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/tips.jpg" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        Dip Potato Bagels in tomato soup.
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>Recommended by</h3>
                      <h4>London Bagel Museum</h4>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/tips.jpg" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        Place a slice of cheddar cheese on top of toasted Spinach Cheese Bagel.
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>Recommended by</h3>
                      <h4>London Bagel Museum</h4>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/tips.jpg" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        Topped with a lot of cream cheese and raspberry jam on a Sesame Bagel.
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>Recommended by</h3>
                      <h4>London Bagel Museum</h4>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/tips.jpg" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        Topped smoked salmon and capers on a Whole Wheat Oats Bagel.
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>Recommended by</h3>
                      <h4>London Bagel Museum</h4>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/tips.jpg" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        Eat Squid Ink Cheese Bagel and Spring Onion Cream Cheese together.
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>Recommended by</h3>
                      <h4>London Bagel Museum</h4>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/tips.jpg" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        Raspberry Cranberry Bagel topped with Honey Walnut Cream Cheese.
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>Recommended by</h3>
                      <h4>London Bagel Museum</h4>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/tips.jpg" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        Double Chocolate Bagel topped with a lot of thick Orange Chocolate Cream.
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>Recommended by</h3>
                      <h4>London Bagel Museum</h4>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/tips.jpg" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        Spread Cream Cheese and Lemon Curd on a Chia Seed Bagel.
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>Recommended by</h3>
                      <h4>London Bagel Museum</h4>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/tips.jpg" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        Eat with Blueberry Bagel and Homemade Ricotta Cheese together.
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>Recommended by</h3>
                      <h4>London Bagel Museum</h4>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/tips.jpg" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>
    </section><!-- End Testimonials Section -->

    <!-- ======= Events Section ======= -->
    <section id="events" class="events">
      <div class="container-fluid" data-aos="fade-up">

        <div class="section-header">
          <h2>Celebrity Visits</h2>
          <p><span>Celebrities who have visited</span> Our Cafe</p>
        </div>

        <div class="slides-3 swiper" data-aos="fade-up" data-aos-delay="100">
          <div class="swiper-wrapper">

          <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(assets/img/celebrity/celebrity1.jpg)">
              <h3>Sooyoung & Tiffany</h3>
              <div class="price align-self-start">Girls' Generation</div>
            </div><!-- End Event item -->

            <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(assets/img/celebrity/celebrity2.jpg)">
              <h3>Jihyo</h3>
              <div class="price align-self-start">Twice</div>
            </div><!-- End Event item -->

            <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(assets/img/celebrity/celebrity3.jpg)">
              <h3>Choi Hyun-Wook</h3>
              <div class="price align-self-start">Actor</div>
            </div><!-- End Event item -->

            <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(assets/img/celebrity/celebrity4.jpg)">
              <h3>Gaeul</h3>
              <div class="price align-self-start">IVE</div>
            </div><!-- End Event item -->

            <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(assets/img/celebrity/celebrity5.jpg)">
              <h3>Chanyeol</h3>
              <div class="price align-self-start">Exo</div>
            </div><!-- End Event item -->

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>
    </section><!-- End Events Section -->

    <!-- ======= Chefs Section ======= -->
    <section id="chefs" class="chefs section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Chefs</h2>
          <p>Our <span>Proffesional</span> Chefs</p>
        </div>

        <div class="row gy-4">

          <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
            <div class="chef-member">
              <div class="member-img">
                <img src="assets/img/chefs/chefs-1.jpg" class="img-fluid" alt="">
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
              <div class="member-info">
                <h4>Walter White</h4>
                <span>Master Chef</span>
                <p>Velit aut quia fugit et et. Dolorum ea voluptate vel tempore tenetur ipsa quae aut. Ipsum exercitationem iure minima enim corporis et voluptate.</p>
              </div>
            </div>
          </div><!-- End Chefs Member -->

          <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="200">
            <div class="chef-member">
              <div class="member-img">
                <img src="assets/img/chefs/chefs-2.jpg" class="img-fluid" alt="">
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
              <div class="member-info">
                <h4>Sarah Jhonson</h4>
                <span>Patissier</span>
                <p>Quo esse repellendus quia id. Est eum et accusantium pariatur fugit nihil minima suscipit corporis. Voluptate sed quas reiciendis animi neque sapiente.</p>
              </div>
            </div>
          </div><!-- End Chefs Member -->

          <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="300">
            <div class="chef-member">
              <div class="member-img">
                <img src="assets/img/chefs/chefs-3.jpg" class="img-fluid" alt="">
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
              <div class="member-info">
                <h4>William Anderson</h4>
                <span>Cook</span>
                <p>Vero omnis enim consequatur. Voluptas consectetur unde qui molestiae deserunt. Voluptates enim aut architecto porro aspernatur molestiae modi.</p>
              </div>
            </div>
          </div><!-- End Chefs Member -->

        </div>

      </div>
    </section><!-- End Chefs Section -->

    

    <!-- ======= Gallery Section ======= -->
    <section id="gallery" class="gallery section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>gallery</h2>
          <p>Check <span>Our Gallery</span></p>
        </div>

        <div class="gallery-slider swiper">
          <div class="swiper-wrapper align-items-center">
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="assets/img/gallery/gallery-1.jpg"><img src="assets/img/gallery/gallery-1.jpg" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="assets/img/gallery/gallery-2.jpg"><img src="assets/img/gallery/gallery-2.jpg" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="assets/img/gallery/gallery-3.jpg"><img src="assets/img/gallery/gallery-3.jpg" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="assets/img/gallery/gallery-4.jpg"><img src="assets/img/gallery/gallery-4.jpg" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="assets/img/gallery/gallery-5.jpg"><img src="assets/img/gallery/gallery-5.jpg" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="assets/img/gallery/gallery-6.jpg"><img src="assets/img/gallery/gallery-6.jpg" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="assets/img/gallery/gallery-7.jpg"><img src="assets/img/gallery/gallery-7.jpg" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="assets/img/gallery/gallery-8.jpg"><img src="assets/img/gallery/gallery-8.jpg" class="img-fluid" alt=""></a></div>
          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>
    </section><!-- End Gallery Section -->

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Contact</h2>
          <p>Need Help? <span>Contact Us</span></p>
        </div>

        <div class="mb-3">
            <iframe style="border:0; width: 100%; height: 350px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3161.9760109369604!2d126.98357707568321!3d37.57918257203532!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x357ca310cdba0e2b%3A0xe923dd83a93dacb9!2sLondon%20Bagel%20Museum%20Anguk!5e0!3m2!1sen!2smy!4v1717252485335!5m2!1sen!2smy" frameborder="0" allowfullscreen></iframe>
        </div><!-- End Google Maps -->

        <div class="row gy-4">

          <div class="col-md-6">
            <div class="info-item  d-flex align-items-center">
              <i class="icon bi bi-map flex-shrink-0"></i>
              <div>
                <h3>Our Address</h3>
                <p>40, Jln KSB 11, Taman Kota Syahbandar, 75200 Melaka </p>
              </div>
            </div>
          </div><!-- End Info Item -->

          <div class="col-md-6">
            <div class="info-item d-flex align-items-center">
              <i class="icon bi bi-envelope flex-shrink-0"></i>
              <div>
                <h3>Email Us</h3>
                <p>londonbagelmuseum@gmail.com</p>
              </div>
            </div>
          </div><!-- End Info Item -->

          <div class="col-md-6">
            <div class="info-item  d-flex align-items-center">
              <i class="icon bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>Call Us</h3>
                <p>+60 11-319 1008</p>
              </div>
            </div>
          </div><!-- End Info Item -->

          <div class="col-md-6">
            <div class="info-item  d-flex align-items-center">
              <i class="icon bi bi-share flex-shrink-0"></i>
              <div>
                <h3>Opening Hours</h3>
                <div><strong>Tue-Sun:</strong> 8AM - 6PM;
                  <strong>Monday:</strong> Closed
                </div>
              </div>
            </div>
          </div><!-- End Info Item -->

        </div>

        <form action="form_contact.php" method="post" role="form" class="php-email-form p-3 p-md-4">
  <div class="row">
    <div class="col-xl-6 form-group">
      <input type="text" name="Name" class="form-control" id="Name" placeholder="Your Name" required>
    </div>
    <div class="col-xl-6 form-group">
      <input type="email" class="form-control" name="Email" id="Email" placeholder="Your Email" required>
    </div>
  </div>
  <div class="form-group">
    <input type="text" class="form-control" name="Subject" id="Subject" placeholder="Subject" required>
  </div>
  <div class="form-group">
    <textarea class="form-control" name="Message" rows="5" placeholder="Message" required></textarea>
  </div>
  <div class="my-3">
    <div class="loading">Loading</div>
    <div class="error-message"></div>
    <div class="sent-message">Your message has been sent. Thank you!</div>
  </div>
  <div class="text-center"><button type="submit" name="BtnContact">Send Message</button></div>
</form>


      </div>
    </section><!-- End Contact Section -->

  </main><!-- End #main -->
  <?php include("lib/footer.php"); ?>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <?php
    if (isset($_SESSION['alert'])) {
        echo "<script>alert('{$_SESSION['alert']}');</script>";
        unset($_SESSION['alert']); // Remove the alert message from session after displaying it
    }
  ?>

</body>
</html>