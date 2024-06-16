<?php
session_start();

if(!isset($_SESSION['MemberID'])) {
  header("Location: registration.php");
  exit();
}

require_once("./lib/db.php");
require_once("./lib/function.php");

$SiteUrl = "http://localhost:80/FYP-Project";
$func = new Functions;
$Select = "";

$MemberID = $_SESSION["MemberID"];
$CurrCart = $_SESSION["Cart"];
$MemEmail = $_SESSION['MemberEmail'];

$cart_query = mysqli_query($db_conn, "SELECT * FROM cart WHERE MemberID='$MemberID' AND CartAddDate=(SELECT MAX(CartAddDate) FROM cart WHERE MemberID='$MemberID')");
$cart_num = mysqli_num_rows($cart_query);

$ship_query = mysqli_query($db_conn, "SELECT * FROM member_address WHERE MemberID='$MemberID' AND AddAddress!='' ORDER BY AddressAddDate DESC");
$ship_num = mysqli_num_rows($ship_query);

if($ship_num > 0)
{
  while($ship_row = $ship_query -> fetch_array())
  {
    $AddName = $func->checkInput($ship_row["AddName"]);
    $AddPhone = $func->checkInput($ship_row["AddPhone"]);
    $AddAddress = $func->checkInput($ship_row["AddAddress"]);
    $AddCity = $func->checkInput($ship_row["AddCity"]);
    $AddPostcode = $func->checkInput($ship_row["AddPostcode"]);
    $AddCountry = $func->checkInput($ship_row["AddCountry"]);
    $AddState = $func->checkInput($ship_row["AddState"]);
    $Select = " selected";
  }
}
else
{
  $AddName = "";
  $AddPhone = "";
  $AddAddress = "";
  $AddCity = "";
  $AddPostcode = "";
  $AddCountry = "Malaysia";
  $AddState = "Melaka";
}

if(isset($_POST["BtnCheck"]))
{
  $OrderType = $_POST["orderType"];  // 0 = delivery, 1 = take away

  if($OrderType == "Delivery")
    $OrderType = 0;

  else
    $OrderType = 1;
}

else if(isset($_POST["BtnModifyAdd"]))
{
  $OrderType = $_POST["orderType"];
  $AddName = $func->checkInput($_POST["ShipName"]);
  $MemEmail = $func->checkInput($_POST["ShipEmail"]);
  $AddPhone = $func->checkInput($_POST["ShipPhone"]);
  $AddAddress = $func->checkInput($_POST["ShipAdd"]);
  $AddCity = $func->checkInput($_POST["ShipCity"]);
  $AddPostcode = $func->checkInput($_POST["ShipPostcode"]);
  $AddCountry = $func->checkInput($_POST["ShipCountry"]);
  $AddState = $func->checkInput($_POST["ShipState"]);
  
  $ShippingFee = $_POST["ShippingFee"];
  $PaymentMethod = $_POST["PaymentMethod"];
}

else
{
  header("Location: cart.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("lib/head.php"); ?>
  <title>Checkout</title>

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
          <h2>Checkout</h2>
        </div>
      </div>
    </div><!-- End Breadcrumbs -->

    <section class="sample-page">
      <div class="checkout_container container" data-aos="fade-up">
        <?php
        if($cart_num == 0)
        {
          echo "Your shopping cart is empty.<br><br>
                <button type='button' class=\"button is-outline\" onclick=\"document.location='$SiteUrl/user/menu.php'\"><i class=\"fa fa-arrow-left\"></i>Continue Shopping</button><br><br>";
        }
        else
        {
          $cart_row = mysqli_fetch_array($cart_query);
          
          $CartID = $cart_row["CartID"];
          $CartSubtotal = $cart_row["CartSubTotal"];
          $CartTotal = $cart_row["CartTotal"];
        
          $item_query = mysqli_query($db_conn, "SELECT * FROM cart_product WHERE CartID='$CartID'");
          $item_num = mysqli_num_rows($item_query);

          if($item_num == 0)
          {
            echo "Your shopping cart is empty.<br><br>
                  <button type='button' class=\"button is-outline\" onclick=\"document.location='$SiteUrl/user/menu.php'\"><i class=\"fa fa-arrow-left\"></i>Continue Shopping</button><br><br>";
          }
          else
          {
            $CheckStock = $func->CheckStock($CartID);
            
            if($CheckStock == 0)
            {
              echo "<script>
                      alert('One or more product is out of stock.');
                      self.location='$SiteUrl/user/cart.php'
                    </script>";
            }

        echo $func->checkoutStep(2);
        echo '<form method="post" action="checkout-review.php" class="cart-form">
                <div class="row checkout-row">
                  <div class="col large-6 medium-6 small-12">
                    <h6 class="cart-title">Recipient Information</h6>';

              echo "<label>Name</label>
                    <input type=\"text\" name=\"Name\" placeholder=\"Enter Name\" required value=".$AddName." >
                    <br>
                    <label>Email</label>
                    <input type=\"email\" name=\"Email\" placeholder=\"Enter Email\" value=\"$MemEmail\" required>
                    <br>
                    <label>Phone</label>
                    <input type=\"text\" name=\"Phone\" placeholder=\"Enter Phone\" value=\"$AddPhone\" required>
                    <br>
                    <label>Address</label>
                    <input type=\"text\" name=\"Address\" placeholder=\"Enter Address\" value=\"$AddAddress\" required>
                    <br>
                    <label>City and Postcode</label>
                    <select id=\"Postcode\" name=\"StateAndPostcode\" value=\"$AddCity - $AddPostcode\" required>
                        <option>Select Postcode</option>
                    </select>
                    <br>
                    <label>State</label>
                    <input type=\"text\" name=\"State\" value=\"$AddState\" placeholder=\"Melaka\" required>
                    <br>
                    <label>Country</label>
                    <input type=\"text\" name=\"Country\" value=\"$AddCountry\" placeholder=\"Malaysia\" required>
                  </div>

                  <div class=\"col large-12 medium-12 small-12 mt-10\">
                  <h6 class=\"cart-title\">Payment Method</h6>";
              
        //payment options
        $payment_query = mysqli_query($db_conn, "SELECT PaymentID, PaymentName, PaymentDesc FROM payment WHERE isUp='1' ORDER BY PaymentID");
        $payment_num = mysqli_num_rows($payment_query);
        
        if($payment_num > 0)
        {
          $no_payment = 1;
          while($payment_row = mysqli_fetch_array($payment_query))
          {
            $PaymentID = $payment_row["PaymentID"];
            $PaymentName = stripslashes($payment_row["PaymentName"]);
            $PaymentDesc = stripslashes($payment_row["PaymentDesc"]);
            
            if(empty($PaymentMethod) && $no_payment == 1)
              $PaymentMethod = $PaymentID;
            
            echo "<div class=\"col-sm-12 mb-20\">
                    <input type=\"radio\" name=\"PaymentMethod\" id='radio_$PaymentID' value=\"$PaymentID\"".$func->displayChecked($PaymentID, $PaymentMethod)." class=\"payment-choice\" />
                    <label for='radio_$PaymentID'>$PaymentName</label>";
        
            if($PaymentDesc)
              echo "<div id=\"payment-".$PaymentID."\" class='payment-desc-box".((empty($PaymentMethod) && $no_payment == 1) || (!empty($PaymentMethod) && $PaymentMethod == $PaymentID) ? '' : ' hide')."'>".$PaymentDesc."</div>";
                      
            echo "</div>";
          
            $no_payment++;
          }
        }
        //payment options	
                      
          echo "</div>
              </div>
              <input type='hidden' name='orderType' value='".$OrderType."'>
              <button type=\"submit\" name=\"BtnCheckout\" class=\"button float-end orderButton\">Continue</button>
            </form>";
              
        }
      }
      ?>

      </div>
    </section>

  </main><!-- End #main -->
  <?php include("lib/footer.php"); ?>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <script>
    loadLocation(<?php echo $AddPostcode;?>);
  </script>
</body>

</html>

<?php $db_conn->close();?>