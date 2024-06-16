<?php
session_start();

if (!isset($_SESSION['MemberID'])) {
  header("Location: registration.php");
  exit();
}

require_once("./lib/db.php");
require_once("./lib/function.php");

$SiteUrl = "http://localhost:80/FYP-Project";
$func = new Functions;
$ShippingFee = 0.00;

$MemberID = $_SESSION["MemberID"];
$CurrCart = $_SESSION["Cart"];
$MemEmail = $_SESSION['MemberEmail'];

$cart_query = mysqli_query($db_conn, "SELECT * FROM cart WHERE MemberID='$MemberID' AND CartAddDate=(SELECT MAX(CartAddDate) FROM cart WHERE MemberID='$MemberID')");
$cart_num = mysqli_num_rows($cart_query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("lib/head.php"); ?>
  <title>Checkout Review</title>

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
          <h2>Checkout Review</h2>
        </div>

      </div>
    </div><!-- End Breadcrumbs -->

    <section class="sample-page">
      <div class="container" data-aos="fade-up">

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
            
            $CheckStock = $func->CheckStock($CartID);
            
            if($CheckStock == 0)
            {
                echo "<script>
                                alert('One or more product is out of stock.');
                                self.location='$SiteUrl/user/cart.php'
                            </script>";
            }

            $item_query = mysqli_query($db_conn, "SELECT * FROM cart_product WHERE CartID='$CartID'") ;
            $item_num = mysqli_num_rows($item_query);
            
            if($item_num == 0)
            {
                echo "Your shopping cart is empty.<br><br>
                        <button type='button' class=\"button is-outline orderButton\" onclick=\"document.location='$SiteUrl/user/menu.php'\"><i class=\"fa fa-arrow-left\"></i>Continue Shopping</button><br><br>";
            }
            else
            {
                // address field
                $AddName = stripslashes($_POST["Name"]);
                $MemEmail = stripslashes($_POST["Email"]);
                $AddPhone = stripslashes($_POST["Phone"]);
                $AddAddress = stripslashes($_POST["Address"]);
                $AddState = $_POST["State"];
                $AddCountry = $_POST["Country"];
                $orderType = $_POST["orderType"];

                // if order type is delivery (0)
                if($orderType == 0)
                    $ShippingFee = 10.00;

                // calculate total with shipping fee
                $NewTotal = $CartTotal + $ShippingFee;

                $stapost = explode(" - ",$_POST["StateAndPostcode"]); // $stapost[0] = city, $stapost[1] = postcode
                $AddPostcode = $stapost[1];
                $AddCity = $stapost[0];
                
                $PaymentMethod = $_POST["PaymentMethod"];
                // END address field
                
                echo $func->checkoutStep(3).
                            "<div class=\"row mb-20\">
                                <div class=\"col large-6 medium-6 small-12\">
                                    <h6 class=\"cart-title\">Delivery Address</h6>
                                    $AddName
                                    <br>$AddAddress,
                                    <br>$AddPostcode $AddCity,
                                    <br>$AddState, $AddCountry
                                    <br>".$AddPhone."
                                    <br>$MemEmail
                                </div>
                            </div>
                            <br>
                            <span class='d-lg-none'><h6 class=\"cart-title\">Products</h6></span>
                            <table class=\"table-listing table-item\">
                                <thead>
                                    <tr>
                                        <th colspan='2'>Product</th>
                                        <th class='text-right'>Price (RM)</th>
                                        <th class='text-right'>Quantity</th>
                                        <th class='text-right'>Total (RM)</th>
                                    </tr>
                                </thead>
                                <tbody>";
                        
                    $no = 1;	
                    while($item_row = mysqli_fetch_array($item_query)) 
                    {	
                        $CartProID = $item_row["CartProID"];
                        $ProID = $item_row["ProID"];
                        $ProPrice = $item_row["ProPrice"];
                        $ProQty = $item_row["ProQty"];
                        $ProTotal = $item_row["ProTotal"];

                        $pro_query = mysqli_query($db_conn, "SELECT ProName FROM product WHERE ProID='$ProID'") ;
                        $pro_row = mysqli_fetch_array($pro_query);
                        $ProName = stripslashes($pro_row["ProName"]);
                        
                        echo "<tr>
                                <td><img src='".$func->productPic($ProID)."' class='img-fluid'></td>
                                <td>
                                    <a style='cursor:default'><strong>".$ProName."</strong></a><br>
                                </td>
                                <td class='text-right'>
                                    <span class='d-lg-none'><strong>Price:</strong> RM</span>
                                    ".$ProPrice."
                                </td>
                                <td class='text-right'>
                                    <span class='d-lg-none'><strong>Quantity:</strong></span>
                                    ".$ProQty."
                                </td>
                                <td class='text-right'>
                                    <span class='d-lg-none'><strong>Total:</strong> RM</span>
                                    ".$ProTotal."
                                </td>
                            </tr>";
                                                
                        $no++;
                    }
        
                        echo "    <tr class='total-row'>
                                    <td colspan='4' class='text-right'>Subtotal</td>
                                    <td class='text-right'><strong><span class='d-lg-none'>RM </span>".$CartSubtotal."</strong></td>
                                  </tr>
                                  <tr class='total-row'>
                                      <td colspan='4' class='text-right'>Shipping Fee</td>
                                      <td class='text-right'><span class='d-lg-none'>RM </span>".$ShippingFee."</td>
                                  </tr>
                                  <tr class='total-row'>
                                      <td colspan='4' class='text-right'><strong>Total</strong></td>
                                      <td class='text-right'><strong><span class='d-lg-none'>RM </span>".$NewTotal."</strong></td>
                                  </tr>
                                </tbody>
                            </table>
                            <div class=\"row mt-40 mb-10\">
                                <div class=\"col large-12 medium-12 small-12\">
                                    <h6 class=\"cart-title\">Pay Via</h6>";
                                
                    //payment options
                    $payment_query = mysqli_query($db_conn, "SELECT PaymentName, PaymentDesc FROM payment WHERE PaymentID='$PaymentMethod' AND isUp='1'");
                    $payment_num = mysqli_num_rows($payment_query);
                    
                    if($payment_num > 0)
                    {
                        $payment_row = mysqli_fetch_array($payment_query);
            
                        $PaymentName = stripslashes($payment_row["PaymentName"]);
                        $PaymentDesc = stripslashes($payment_row["PaymentDesc"]);
                        
                        echo "<div class=\"col-sm-12\">
                                            <label>$PaymentName</label>";
                
                        if($PaymentDesc)
                            echo "<div class='payment-desc-box' style='margin-left:0px'>".$PaymentDesc."</div>";
                                            
                        echo "</div>";
                    }
                    //payment options	
                                        
                    echo "</div>
                            </div>";
                
                $BtnText = "Proceed";
                if($NewTotal > 0)
                    $BtnText = "Proceed with Payment";
                    
                    echo "<form id=\"checkoutForm\" method=\"post\" action=\"checkout-process.php\">
                            <input type='hidden' name='orderType' value='".$orderType."'>
                            <input type=\"hidden\" name=\"ShipName\" value=\"".$func->checkInput($AddName)."\">
                            <input type=\"hidden\" name=\"ShipEmail\" value=\"".$func->checkInput($MemEmail)."\">
                            <input type=\"hidden\" name=\"ShipPhone\" value=\"".$func->checkInput($AddPhone)."\">
                            <input type=\"hidden\" name=\"ShipAdd\" value=\"".$func->checkInput($AddAddress)."\">
                            <input type=\"hidden\" name=\"ShipPostcode\" value=\"".$func->checkInput($AddPostcode)."\">
                            <input type=\"hidden\" name=\"ShipCity\" value=\"".$func->checkInput($AddCity)."\">
                            <input type=\"hidden\" name=\"ShipState\" value=\"".$func->checkInput($AddState)."\">
                            <input type=\"hidden\" name=\"ShipCountry\" value=\"".$func->checkInput($AddCountry)."\">
                            <input type=\"hidden\" name=\"PaymentMethod\" value=\"$PaymentMethod\">
                            <input type=\"hidden\" name=\"ShippingFee\" value=\"$ShippingFee\">
                            <input type=\"hidden\" name=\"Checkout\" value=\"1\">
                            <button type=\"submit\" name=\"BtnModifyAdd\" id=\"BtnModifyAdd\" class=\"button is-outline orderButton flaot-start\">Modify Address</button>
                            <button type=\"submit\" name=\"BtnCheckout\" id=\"BtnFinalCheckout\" class=\"button orderButton float-end\">$BtnText</button>
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
    $(document).on('click touchstart', '#BtnModifyAdd', function()
	{
        $("#checkoutForm").attr("action", window.location.origin + "/FYP-Project/user/checkout.php");
		$("#checkoutForm").submit();
	});
  </script>

</body>
</html>