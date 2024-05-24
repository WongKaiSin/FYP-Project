<?php
require_once("lib/db.php");
require_once("./lib/function.php");

$SiteUrl = "http://localhost:80/FYP-Project";
$func = new Functions;

if(isset($_POST["Checkout"]) && $_POST["Checkout"] == "1")
{
	$ShippingFee = $_POST["ShippingFee"];
	$checkout .= checkCart($ShippingFee);
	
	$cart_query = mysqli_query($db_conn, "SELECT * FROM js_store_cart WHERE UserID='$UserID' ORDER BY CartID DESC");
	$cart_row = mysqli_fetch_array($cart_query);
	
	$CartID = $cart_row["CartID"];
	$CartSubtotal = $cart_row["CartSubtotal"];
	$CartCouponCode = $cart_row["CartCouponCode"];
	$CartCouponAmount = $cart_row["CartCouponAmount"];
	$CartCouponText = $cart_row["CartCouponText"];
	$CartCouponError = $cart_row["CartCouponError"];
	$CartReferralCode = $cart_row["CartReferralCode"];
	$CartReferralAmount = $cart_row["CartReferralAmount"];
	$CartReferralError = $cart_row["CartReferralError"];
	$CartCredits = $cart_row["CartCredits"];
	$CartTotal = $cart_row["CartTotal"];
	
	$CheckStock = CheckStock($CartID);
	
	if($CheckStock == 0)
	{
		$checkout .= "<script>
						  alert('One or more product is out of stock.');
						  self.location='$SiteUrl/cart/'
					  </script>";
	}
	
	if(!empty($CartCouponError) || !empty($CartReferralError))
		$checkout .= "<script>self.location='$SiteUrl/cart/'</script>";
	else
	{
		if($CartSubtotal > 0)
		{
			$checkout .= "<p class='text-center'>
							  Please wait while your transaction is being processing...<br>
							  Please do not refresh the page or click back button while waiting this transaction, <br />
							  else this transaction will be interrupted.
						  </p>";
					
			// address field
			$ShipName = addslashes($_POST["ShipName"]);
			$ShipEmail = addslashes($_POST["ShipEmail"]);
			$ShipPhone = addslashes($_POST["ShipPhoneCode"]).addslashes($_POST["ShipPhone"]);
			$ShipAdd = addslashes($_POST["ShipAdd"]);
			$ShipAdd2 = addslashes($_POST["ShipAdd2"]);
			$ShipCity = addslashes($_POST["ShipCity"]);
			$ShipPostcode = addslashes($_POST["ShipPostcode"]);
			$ShipCountry = $_POST["ShipCountry"];
			$ShipState = addslashes($_POST["ShipState"]);
			$ShipNew = $_POST["ShipNew"];
			$BillDiff = $_POST["BillDiff"];
			
			$ShipCountryName = getLocationName($ShipCountry);
			$ShipStateName = getLocationName($ShipState);
			
			if($BillDiff == "1")
			{
				$BillName = addslashes($_POST["BillName"]);
				$BillEmail = addslashes($_POST["BillEmail"]);
				$BillPhone = addslashes($_POST["BillPhoneCode"]).addslashes($_POST["BillPhone"]);
				$BillAdd = addslashes($_POST["BillAdd"]);
				$BillAdd2 = addslashes($_POST["BillAdd2"]);
				$BillCity = addslashes($_POST["BillCity"]);
				$BillPostcode = addslashes($_POST["BillPostcode"]);
				$BillCountry = $_POST["BillCountry"];
				$BillState = addslashes($_POST["BillState"]);
				$BillNew = $_POST["BillNew"];
				
				$BillCountryName = getLocationName($BillCountry);
				$BillStateName = getLocationName($BillState);
			}
			else
			{
				$BillName = $ShipName;
				$BillEmail = $ShipEmail;
				$BillPhone = $ShipPhoneCode.$ShipPhone;
				$BillAdd = $ShipAdd;
				$BillAdd2 = $ShipAdd2;
				$BillCity = $ShipCity;
				$BillPostcode = $ShipPostcode;
				$BillCountry = $ShipCountry;
				$BillCountryName = $ShipCountryName;
				$BillState = $ShipState;
				$BillStateName = $ShipStateName;
			}
			
			$OrderRemarks = addslashes($_POST["OrderRemarks"]);
			$PaymentMethod = $_POST["PaymentMethod"];
			// END address field
		
			$payment_query = mysqli_query($db_conn, "SELECT * FROM js_store_payment WHERE PaymentID=\"$PaymentMethod\" AND isUp=\"1\" AND admin_disabled='0'");
			$payment_num = mysqli_num_rows($payment_query);
			
			if($payment_num > 0)
			{
				$payment_row = mysqli_fetch_array($payment_query);
				
				$PaymentName = $payment_row["PaymentName"];
				$PaymentUser = $payment_row["PaymentUser"];
				$PaymentKey = $payment_row["PaymentKey"];
				$PaymentCurr = $payment_row["PaymentCurr"];
				$PaymentRequest = $payment_row["PaymentRequest"];
				$PaymentResponse = $payment_row["PaymentResponse"];
			}
		
			$OrderStatus = "Order Confirmed";
			if($CartTotal > 0)
				$OrderStatus = "Waiting for Payment";
		
			mysqli_query($db_conn, "INSERT INTO js_store_order (UserID, OrderSubtotal, OrderCouponCode, OrderCouponText, OrderCouponAmount, OrderReferralCode, OrderReferralAmount, OrderCreditsOri, OrderCredits, OrderShipping, OrderTotal, OrderCurrCode, OrderCurrSymb, OrderPaymentID, OrderPayment, OrderRemarks, OrderStatus, strAddDate) VALUES ('$UserID', '".currConvert($CartSubtotal, 1)."', '$CartCouponCode', '$CartCouponText', '".currConvert($CartCouponAmount, 1)."', '$CartReferralCode', '".currConvert($CartReferralAmount, 1)."', '".$CartCredits."', '".currConvert($CartCredits, 1)."', '".currConvert($ShippingFee, 1)."', '".currConvert($CartTotal, 1)."', '$CurrCode', '$CurrSymbol', '$PaymentMethod', '$PaymentName', '$OrderRemarks', '$OrderStatus', DATE_ADD(NOW(), INTERVAL 8 HOUR))");
			$OrderID = mysqli_insert_id($db_conn);
			
			$OrderNo = date("Ymd")."-".str_pad($OrderID, 5, "0", STR_PAD_LEFT); 
			mysqli_query($db_conn, "UPDATE js_store_order SET OrderNo='$OrderNo' WHERE OrderID='$OrderID'");
			
			$item_query = mysqli_query($db_conn, "SELECT * FROM js_store_cart_products WHERE CartID='$CartID'") ;
			while($item_row = mysqli_fetch_array($item_query)) 
			{	
				$CartProID = $item_row["CartProID"];
				$ProID = $item_row["ProID"];
				$ProName = addslashes($item_row["ProName"]);
				$ProSku = addslashes($item_row["ProSku"]);
				$ProVarID = $item_row["ProVarID"];
				$ProVarName = addslashes($item_row["ProVarName"]);
				$ProPrice = $item_row["ProPrice"];
				$ColorID = $item_row["ColorID"];
				$ProQty = $item_row["ProQty"];
				$ProTotal = $item_row["ProTotal"];
				$ProRemarks = addslashes($item_row["ProRemarks"]);
				
				mysqli_query($db_conn, "INSERT INTO js_store_order_products (OrderID, ProID, ProName, ProSku, ProVarID, ProVar, ColorID, ProPrice, ProQty, ProTotal, ProRemarks) VALUES ('$OrderID', '$ProID', '$ProName', '$ProSku', '$ProVarID', '$ProVarName', '$ColorID', '".currConvert($ProPrice, 1)."', '$ProQty', '".currConvert($ProTotal, 1)."', '$ProRemarks')");
			}
			
			// address
			mysqli_query($db_conn, "INSERT INTO js_store_order_address (OrderID, BillName, BillPhone, BillEmail, BillAdd, BillAdd2, BillPostcode, BillCity, BillState, BillCountry, ShipName, ShipPhone, ShipEmail, ShipAdd, ShipAdd2, ShipPostcode, ShipCity, ShipState, ShipCountry) VALUES ('$OrderID', '$BillName', '$BillPhone', '$BillEmail', '$BillAdd', '$BillAdd2', '$BillPostcode', '$BillCity', '$BillStateName', '$BillCountryName', '$ShipName', '$ShipPhone', '$ShipEmail', '$ShipAdd', '$ShipAdd2', '$ShipPostcode', '$ShipCity', '$ShipStateName', '$ShipCountryName')");
			
			if($ShipNew == "1")
			{
				mysqli_query($db_conn, "INSERT INTO js_store_member_address (UserID, AddType, AddName, AddPhone, AddEmail, AddAddress, AddAddress2, AddPostcode, AddCity, AddState, AddCountry, strAddDate) VALUES ('$UserID', 'shipping', '$ShipName', '$ShipPhone', '$ShipEmail', '$ShipAdd', '$ShipAdd2', '$ShipPostcode', '$ShipCity', '$ShipState', '$ShipCountry', DATE_ADD(NOW(), INTERVAL 8 HOUR))");
			}
			
			if($BillNew == "1")
			{
				mysqli_query($db_conn, "INSERT INTO js_store_member_address (UserID, AddType, AddName, AddPhone, AddEmail, AddAddress, AddAddress2, AddPostcode, AddCity, AddState, AddCountry, strAddDate) VALUES ('$UserID', 'billing', '$BillName', '$BillPhone', '$BillEmail', '$BillAdd', '$BillAdd2', '$BillPostcode', '$BillCity', '$BillState', '$BillCountry', DATE_ADD(NOW(), INTERVAL 8 HOUR))");
			}
			// END address
			
			
			mysqli_query($db_conn, "INSERT INTO js_store_order_status (OrderID, StatusName, StatusDate) VALUES ('$OrderID', '$OrderStatus', DATE_ADD(NOW(), INTERVAL 8 HOUR))");
			$StatusID = mysqli_insert_id($db_conn);
				
					
			// delete from cart
			mysqli_query($db_conn, "DELETE FROM js_store_cart_products WHERE CartID='$CartID'");
			mysqli_query($db_conn, "DELETE FROM js_store_cart WHERE CartID='$CartID'");
			// END delete from cart
			
			// redirect to payment page
			// ORDER DETAIL
			$OrderAdd = stripslashes($ShipAdd).", ";
								  
		if(!empty($ShipAdd2))
			$OrderAdd .= stripslashes($ShipAdd2).", ";
								
		  $OrderAdd .= "$ShipPostcode ".stripslashes($ShipCity).", $ShipStateName, $ShipCountryName";
		  
			$OrderDate = date("jS F Y, g:i a");
			$OrderDetail = "<hr style='border:0px; border-bottom:2px dashed #DDD'>
							<h3>Below Order will be Delivered to</h3>
							".stripslashes($ShipName)."<br>
							".$OrderAdd."
							<br>Phone: $ShipPhone
							<br>Email Address: $ShipEmail<br><br>
							<hr style='border:0px; border-bottom:2px dashed #DDD'>
							<h3>Products</h3>
							<table cellpadding='0' cellspacing='0' style='width:100%'>";
							
			$item_query = mysqli_query($db_conn, "SELECT * FROM js_store_order_products WHERE OrderID='$OrderID'");	  
			
			$OrderItems = "";
			$no=1;
			while ($item_row = mysqli_fetch_array($item_query)) 
			{	
				$ProID = $item_row["ProID"];
				$ProName = stripslashes($item_row["ProName"]);
				$ProSku = stripslashes($item_row["ProSku"]);
				$ProVarID = $item_row["ProVarID"];
				$ProVar = stripslashes($item_row["ProVar"]);
				$ProPrice = $item_row["ProPrice"];
				$ProQty = $item_row["ProQty"];
				$ProTotal = $item_row["ProTotal"];
				$ProRemarks = stripslashes($item_row["ProRemarks"]);
				
				$OrderItems .= "<br>".$no.". $ProName <em>(Color: $ProVar)</em>";
				
				$OrderDetail .= "<tr>
									<td width='80px' style='border-bottom:1px solid #DDD; padding:7px; padding-left:0px'>
										<img src='".productPic($ProVarID, $ProVar, "S")."' style='width:80px'>
									</td>
									<td style='border-bottom:1px solid #DDD; padding:7px; padding-right:0px'>
										<strong>".$ProName."</strong><br>
										<span style='display:block; color:#777; margin-bottom:10px; line-height:1.4; font-size:12px'>";
					
					  if(!empty($ProVarID))			  
						  $OrderDetail .= "Size: $ProVarID<br>";
						  
						  $OrderDetail .= "Color: $ProVar";

					  if($ProSku == "preorder")
						  $OrderDetail .= "<br><span style='color:red'>* Pre-Order</span>";

						$OrderDetail .= "</span>
										  <span style='color:red'>".$CurrCode.formatNumber($ProPrice)."</span><br>
										  Quantity: $ProQty";
										  
					if(!empty($ProRemarks))
					{
						$OrderDetail .= "<div style='font-size:12px; margin-top:10px'>
											<strong>Remarks:</strong><br>
											".nl2br($ProRemarks)."
										</div>";
					}
	
						
				$OrderDetail .= "</td>
							  </tr>";
				
				$no++;
			}				
							
			$OrderDetail .= "</table>";
			// END ORDER DETAIL
			
			$OrderAmount = $CurrCode." ".currConvert($CartTotal, 0, 0);
			
			$custom_msg = $OrderNo."######".$StatusID."######".$OrderDetail."######".$OrderItems."######".$OrderAdd."######".$OrderAmount."######".stripslashes($OrderRemarks);
			if($CartTotal > 0)
			{
				if($payment_num > 0)
				{
					$OrderFinalTotal = $CartTotal;
					include "data/lib/payment-config.php";
				}
				
				// send email (waiting payment)
				// $func->send_email(7, $UserName, $UserEmail, $custom_msg);
				// END send email (waiting payment)
				
				if(!empty($payment_form))
				{			
					$checkout .= $payment_form.
								  "<script type='text/javascript'>
									  $(document).ready(function()
									  {
										  $('#PaymentForm').submit();
									  });
								  </script>";
				}
				else
					$checkout .= "<script>self.location='$SiteUrl/checkout-complete/$OrderID/'</script>";
			}
			else
			{
				// send email (do not need payment)
				// $func->send_email(23, $UserName, $UserEmail, $custom_msg);
				// END send email (do not need payment)
				
				$checkout .= "<script>self.location='$SiteUrl/checkout-complete/$OrderID/'</script>";
			}
			// END redirect to payment page
		}
		else
			$error = 1;
	}
}
else
	$error = 1;
		
if($error == 1)
{
	$checkout .= "<script>
					  alert('An error has occurred during the transaction. Please try again.');
					  self.location='$SiteUrl/cart/'
				  </script>";
}

?>