
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("lib/head.php"); ?>
  <title>Customer Registration | London Bagel Museum</title>
  <script>
  function togglePasswordVisibility(inputId) {
    var x = document.getElementById(inputId);
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }
</script>
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
          <h2>Sample Inner Page</h2>
          <ol>
            <li><a href="index.php">Home</a></li>
            <li>Sample Inner Page</li>
          </ol>
        </div>

      </div>
    </div><!-- End Breadcrumbs -->

    <section id="registration" class="registration">
      <div class="container" data-aos="fade-up">
        <div class="row g-0">

        <ul class="nav nav-tabs d-flex justify-content-center" data-aos="fade-up" data-aos-delay="200">

          <li class="nav-item">
            <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#menu-starters">
              <h4>Login</h4>
            </a>
          </li><!-- End tab nav item -->

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu-breakfast">
              <h4>Sign up</h4>
            </a>
          </li><!-- End tab nav item -->
        </ul>

        <div class="tab-content" data-aos="fade-up" data-aos-delay="300">

        <div class="tab-pane fade active show" id="menu-starters">
    <div class="tab-header text-center">
        <p>Already Have An Account?</p>
        <h3>Login</h3>
    </div>

    <div class="row gy-5">
        <div class="col-lg-6">
            <div class="login-img" style="background-image: url(assets/img/login.jpg);" data-aos="zoom-out" data-aos-delay="200">
                <!-- Image here -->
            </div>
        </div>
        
        <div class="col-lg-6 d-flex align-items-center reservation-form-bg">
        <form action="form_login.php" method="post" role="form" class="login-form" data-aos="fade-up" data-aos-delay="100">
          <div class="row gy-4">
              <div class="col-md-12">
                  <div class="form-group">
                      <label for="MemberEmail"><b>Email</b></label>
                      <input type="email" class="form-control" name="MemberEmail" id="MemberEmail" placeholder="Please Enter Your Email" data-rule="email" data-msg="Please enter a valid email">
                      <div class="validate"></div>
                  </div>
              </div>

              <div class="col-md-12">
                  <div class="form-group">
                      <label for="MemberPass"><b>Password</b></label>
                      <div class="password-input">
                          <input type="password" name="MemberPass" class="form-control" id="MemberPass" placeholder="Please Enter Your Password" data-rule="minlen:4" data-msg="Please enter at least 4 characters">
                          <span class="toggle-password" onclick="togglePasswordVisibility('MemberPass')">Show Password</span>
                      </div>
                      <div class="validate"></div>
                  </div>
              </div>
          </div>

          <div class="forgot-password">
              <a href="forgot-password.php">Forgot Password?</a>
          </div>

          <div class="text-center">
              <button type="submit" name="loginbtn" class="btn btn-primary">Login</button>
          </div>
      </form>

        </div><!-- End Form -->
    </div><!-- End Login Content -->
</div>

<div class="tab-pane fade" id="menu-breakfast">
    <div class="tab-header text-center">
        <p>New Customer? Please Create An Account</p>
        <h3 style="color: #ec2727;">Sign Up</h3>
    </div>

    <div class="row gy-5">
        <div class="col-lg-6">
            <div class="login-img" style="background-image: url(assets/img/login1.jpg);" data-aos="zoom-out" data-aos-delay="200">
                <!-- Image here -->
            </div>
        </div>

        <div class="col-lg-6 d-flex align-items-center reservation-form-bg">
            <form action="form_signup.php" method="post" role="form" class="signup-form" data-aos="fade-up" data-aos-delay="100">
                <div class="row gy-4">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="MemberEmail"><b>Email</b></label>
                            <input type="email" class="form-control" name="MemberEmail" id="MemberEmail" placeholder="Please Enter Your Email" required>
                            <div class="validate"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                          <label for="SignupMemberPass"><b>Password</b></label>
                          <div class="password-input">
                              <input type="password" name="MemberPass" class="form-control" id="SignupMemberPass" placeholder="Please Enter Your Password" data-rule="minlen:4" data-msg="Please enter at least 4 characters">
                              <span class="toggle-password" onclick="togglePasswordVisibility('SignupMemberPass')">Show Password</span>
                          </div>
                          <div class="validate"></div>
                      </div>
                  </div>

                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="CfmPass"><b>Confirm Password</b></label>
                          <div class="password-input">
                               <input type="password" name="CfmPass" class="form-control" id="CfmPass" placeholder="Please Enter Confirm Password" required>
                              <span class="toggle-password" onclick="togglePasswordVisibility('CfmPass')">Show Password</span>
                          </div>
                          <div class="validate"></div>
                      </div>
                  </div>

                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="MemberName"><b>Name</b></label>
                            <input type="text" name="MemberName" class="form-control" id="MemberName" placeholder="Please Enter Your Name" required>
                            <div class="validate"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="MemberPhone"><b>Phone Number</b></label>
                            <input type="text" class="form-control" name="MemberPhone" id="MemberPhone" placeholder="Please Enter Your Phone Number" required>
                            <div class="validate"></div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" name="signupbtn" class="btn btn-primary">Sign Up</button>
                </div>
            </form>
        </div><!-- End Form -->
    </div><!-- End Sign Up Content -->
</div>

        </div><!-- End tab content -->
        </div>
      </div>
    </section>

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