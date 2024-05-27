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
	echo $EMPTY_TEXT_VIEWCART.".<br><br>
				<button type='button' class=\"button is-outline\" onclick=\"document.location='$SiteUrl/".PRODUCTS_PREFIX."/'\"><i class=\"fa fa-arrow-left\"></i>$BACK_VIEWCART</button><br><br>";
}
else
{
	$cart_row = mysqli_fetch_array($cart_query);
	
	$CartID = $cart_row["CartID"];
	$CartSubtotal = $cart_row["CartSubtotal"];
	$CartCouponCode = $cart_row["CartCouponCode"];
	$CartCouponText = $cart_row["CartCouponText"];
	$CartCouponAmount = $cart_row["CartCouponAmount"];
	$CartCouponError = $cart_row["CartCouponError"];
	$CartReferralCode = $cart_row["CartReferralCode"];
	$CartReferralAmount = $cart_row["CartReferralAmount"];
	$CartReferralError = $cart_row["CartReferralError"];
	$CartCredits = $cart_row["CartCredits"];
	$CartTotal = $cart_row["CartTotal"];
	
	$CheckStock = $func->CheckStock($CartID);
	
	if($CheckStock == 0)
	{
		echo "<script>
						  alert('One or more product is out of stock.');
						  self.location='$SiteUrl/cart/'
					  </script>";
	}
	
	if(!empty($CartCouponError) || !empty($CartReferralError))
		echo "<script>self.location='$SiteUrl/cart/'</script>";
	else
	{
		$item_query = mysqli_query($db_conn, "SELECT * FROM js_store_cart_products WHERE CartID='$CartID'") ;
		$item_num = mysqli_num_rows($item_query);
		
		if($item_num == 0)
		{
			echo $EMPTY_TEXT_VIEWCART.".<br><br>
						<button type='button' class=\"button is-outline\" onclick=\"document.location='$SiteUrl/".PRODUCTS_PREFIX."/'\"><i class=\"fa fa-arrow-left\"></i>$BACK_VIEWCART</button><br><br>";
		}
		else
		{
			// address field
			$ShipName = stripslashes($_POST["ShipName"]);
			$ShipEmail = stripslashes($_POST["ShipEmail"]);
			$ShipPhoneCode = stripslashes($_POST["ShipPhoneCode"]);
			$ShipPhone = stripslashes($_POST["ShipPhone"]);
			$ShipAdd = stripslashes($_POST["ShipAdd"]);
			$ShipAdd2 = stripslashes($_POST["ShipAdd2"]);
			$ShipCity = stripslashes($_POST["ShipCity"]);
			$ShipPostcode = stripslashes($_POST["ShipPostcode"]);
			$ShipCountry = $_POST["ShipCountry"];
			$ShipState = stripslashes($_POST["ShipState"]);
			$ShipNew = $_POST["ShipNew"];
			$BillDiff = $_POST["BillDiff"];
			
			// $ShipCountryName = getLocationName($ShipCountry);
			// $ShipStateName = getLocationName($ShipState);
			
			if($BillDiff == "1")
			{
				$BillName = stripslashes($_POST["BillName"]);
				$BillEmail = stripslashes($_POST["BillEmail"]);
				$BillPhoneCode = stripslashes($_POST["BillPhoneCode"]);
				$BillPhone = stripslashes($_POST["BillPhone"]);
				$BillAdd = stripslashes($_POST["BillAdd"]);
				$BillAdd2 = stripslashes($_POST["BillAdd2"]);
				$BillCity = stripslashes($_POST["BillCity"]);
				$BillPostcode = stripslashes($_POST["BillPostcode"]);
				$BillCountry = $_POST["BillCountry"];
				$BillState = stripslashes($_POST["BillState"]);
				$BillNew = $_POST["BillNew"];
				
				// $BillCountryName = getLocationName($BillCountry);
				// $BillStateName = getLocationName($BillState);
			}
			else
			{
				$BillName = $ShipName;
				$BillEmail = $ShipEmail;
				$BillPhoneCode = $ShipPhoneCode;
				$BillPhone = $ShipPhone;
				$BillAdd = $ShipAdd;
				$BillAdd2 = $ShipAdd2;
				$BillCity = $ShipCity;
				$BillPostcode = $ShipPostcode;
				$BillCountry = $ShipCountry;
				$BillCountryName = $ShipCountryName;
				$BillState = $ShipState;
				$BillStateName = $ShipStateName;
			}
			
			$OrderRemarks = stripslashes($_POST["OrderRemarks"]);
			$PaymentMethod = $_POST["PaymentMethod"];
			// END address field
			
			echo $func->checkoutStep(3).
						 "<div class=\"row mb-20\">
							  <div class=\"col large-6 medium-6 small-12\">
								  <h6 class=\"cart-title\">Delivery Address</h6>
								  $ShipName
								  <br>$ShipAdd,";
								  
				if(!empty($ShipAdd2))
					echo "<br>".$ShipAdd2.",";
									  
					echo "<br>$ShipPostcode $ShipCity,
								  <br>$ShipStateName, $ShipCountryName
								  <br>$ShipPhoneCode".$ShipPhone."
								  <br>$ShipEmail
							  </div>
							  <div class=\"col large-6 medium-6 small-12\">
								  <h6 class=\"cart-title\">Billing Address</h6>
								  $BillName
								  <br>$BillAdd,";
								  
				if(!empty($BillAdd2))
					echo "<br>".$BillAdd2.",";
									  
					echo "<br>$BillPostcode $BillCity,
								  <br>$BillStateName, $BillCountryName
								  <br>$BillPhoneCode".$BillPhone."
								  <br>$BillEmail
							  </div>
						  </div>
						  <span class='hide-for-large'><h6 class=\"cart-title\">Products</h6></span>
						  <table class=\"table-listing table-item\">
							  <thead>
								  <tr>
									  <th colspan='2'>$PRODUCT_VIEWCART</th>
									  <th class='text-right'>$PRICE_VIEWCART ($CurrCode)</th>
									  <th class='text-right'>Quantity</th>
									  <th class='text-right'>$TOTAL_VIEWCART ($CurrCode)</th>
								  </tr>
							  </thead>
							  <tbody>";
					  
				$no = 1;	
				while ($item_row = mysqli_fetch_array($item_query)) 
				{	
					$CartProID = $item_row["CartProID"];
					$ProID = $item_row["ProID"];
					$ProName = stripslashes($item_row["ProName"]);
					$ProSku = stripslashes($item_row["ProSku"]);
					$ProVarID = $item_row["ProVarID"];
					$ProVarName = stripslashes($item_row["ProVarName"]);
					$ProPrice = $item_row["ProPrice"];
					$ProQty = $item_row["ProQty"];
					$ProTotal = $item_row["ProTotal"];
					$ProRemarks = stripslashes($item_row["ProRemarks"]);
					
					echo "<tr>
                                      <td><img src='".$func->productPic($ProID)."' class='img-fluid'></td>
									  <td>
										  <a style='cursor:default'><strong>".$ProName."</strong></a><br>
										  <span class='text-small'>";
							
							if(!empty($ProVarID))			  
								echo "Size: $ProVarID<br>";
								
								echo "Color: $ProVarName";
							
							if($ProSku == "preorder")
								echo "<br><span style='color:red'>* Pre-Order</span>";
										  
							echo "</span>";
							
							if(!empty($ProRemarks))
							{
							echo "<div class=\"listing-remarks-box listing-remarks-box-border\">
											  <strong>Remarks:</strong><br>
											  ".nl2br($ProRemarks)."
										  </div>";
							}
							
						echo "</td>
									  <td class='text-right'>
										  <span class='hide-for-large'><strong>$PRICE_VIEWCART:</strong> $CurrCode</span>
										  ".$ProPrice."
									  </td>
									  <td class='text-right'>
										  <span class='hide-for-large'><strong>Quantity:</strong></span>
										  ".$ProQty."
									  </td>
									  <td class='text-right'>
										  <span class='hide-for-large'><strong>$TOTAL_VIEWCART:</strong> $CurrCode</span>
										  ".$ProTotal."
									  </td>
								  </tr>";
											
					$no++;
				}
	
					echo "<tr class='total-row'>
									  <td colspan='4' class='text-right'>$SUBTOTAL_VIEWCART</td>
									  <td class='text-right'><strong><span class='hide-for-large'>$CurrCode </span>".$CartSubtotal."</strong></td>
								  </tr>
								  <tr class='total-row'>
									  <td colspan='4' class='text-right'>Shipping Fee</td>
									  <td class='text-right'><span class='hide-for-large'>$CurrCode </span>".$ShippingFee."</td>
								  </tr>";
								  
					echo "<tr class='total-row'>
									  <td colspan='4' class='text-right'><strong>$TOTAL_VIEWCART</strong></td>
									  <td class='text-right'><strong><span class='hide-for-large'>$CurrCode </span>".$CartTotal."</strong></td>
								  </tr>
							  </tbody>
						  </table>
						  <div class=\"row mt-40 mb-10\">
							  <div class=\"col large-12 medium-12 small-12\">
								  <h6 class=\"cart-title\">Pay Via</h6>";
							
				  //payment options
				  $payment_query = mysqli_query($db_conn, "SELECT PaymentName, PaymentDesc FROM js_store_payment WHERE PaymentID='$PaymentMethod' AND isUp='1' AND admin_disabled='0'");
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
		
		if(!empty($OrderRemarks))
		{				  
			echo "<div class=\"row mb-20\">
							  <div class=\"col large-12 medium-12 small-12\">
								  <h6 class=\"cart-title\">Order's Remarks</h6>
								  <div class='payment-desc-box' style='margin-left:0px'>".nl2br($OrderRemarks)."</div>
							  </div>
						  </div>";
		}
		
		$BtnText = "Proceed";
		if($CartTotal > 0)
			$BtnText = "Proceed with Payment";
			
			echo "<form id=\"checkoutForm\" method=\"post\" action=\"$SiteUrl/checkout-process/\">
							  <input type=\"hidden\" name=\"ShipName\" value=\"".$func->checkInput($ShipName)."\">
							  <input type=\"hidden\" name=\"ShipEmail\" value=\"".$func->checkInput($ShipEmail)."\">
							  <input type=\"hidden\" name=\"ShipPhoneCode\" value=\"".$func->checkInput($ShipPhoneCode)."\">
							  <input type=\"hidden\" name=\"ShipPhone\" value=\"".$func->checkInput($ShipPhone)."\">
							  <input type=\"hidden\" name=\"ShipAdd\" value=\"".$func->checkInput($ShipAdd)."\">
							  <input type=\"hidden\" name=\"ShipAdd2\" value=\"".$func->checkInput($ShipAdd2)."\">
							  <input type=\"hidden\" name=\"ShipPostcode\" value=\"".$func->checkInput($ShipPostcode)."\">
							  <input type=\"hidden\" name=\"ShipCity\" value=\"".$func->checkInput($ShipCity)."\">
							  <input type=\"hidden\" name=\"ShipState\" value=\"".$func->checkInput($ShipState)."\">
							  <input type=\"hidden\" name=\"ShipCountry\" value=\"".$func->checkInput($ShipCountry)."\">
							  <input type=\"hidden\" name=\"ShipNew\" value=\"$ShipNew\">
							  <input type=\"hidden\" name=\"OrderRemarks\" value=\"".$func->checkInput($OrderRemarks)."\">
							  <input type=\"hidden\" name=\"PaymentMethod\" value=\"$PaymentMethod\">
							  <input type=\"hidden\" name=\"ShippingFee\" value=\"$ShippingFee\">
							  <input type=\"hidden\" name=\"Checkout\" value=\"1\">
							  <button type=\"submit\" name=\"BtnModifyAdd\" id=\"BtnModifyAdd\" class=\"button is-outline pull-left\">Modify Address</button>
							  <button type=\"submit\" name=\"BtnCheckout\" id=\"BtnFinalCheckout\" class=\"button pull-right\">$BtnText</button>
						  </form>";
		}
	}
}
        
        ?>

      </div>
    </section>

  </main><!-- End #main -->
  <?php include("lib/footer.php"); ?>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

</body>
</html>