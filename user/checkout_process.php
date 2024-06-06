<?php
require_once("lib/db.php");
require_once("lib/function.php");

$SiteUrl = "http://localhost:80/FYP-Project";
$func = new Functions;

if(isset($_POST["Checkout"]) && $_POST["Checkout"] == "1")
{
	$ShippingFee = $_POST["ShippingFee"];
	$checkout .= $func->checkCart($ShippingFee);
	
	$cart_query = mysqli_query($db_conn, "SELECT * FROM cart WHERE MemberID='$MemberID' ORDER BY CartID DESC");
	$cart_row = mysqli_fetch_array($cart_query);
	
	$CartID = $cart_row["CartID"];
	$CartSubtotal = $cart_row["CartSubTotal"];
	$CartTotal = $cart_row["CartTotal"];
	
	$CheckStock = $func->CheckStock($CartID);
	
	if($CheckStock == 0)
	{
		$checkout .= "<script>
						  alert('One or more product is out of stock.');
						  self.location='$SiteUrl/user/cart.php'
					  </script>";
	}

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
		$ShipPhone = stripslashes($_POST["ShipPhone"]);
		$ShipAdd = addslashes($_POST["ShipAdd"]);
		$ShipCity = addslashes($_POST["ShipCity"]);
		$ShipPostcode = addslashes($_POST["ShipPostcode"]);
		$ShipCountry = $_POST["ShipCountry"];
		$ShipState = addslashes($_POST["ShipState"]);
		$ShipNew = $_POST["ShipNew"];
		
		$PaymentMethod = $_POST["PaymentMethod"];
		// END address field
	
		$payment_query = mysqli_query($db_conn, "SELECT * FROM payment WHERE PaymentID=\"$PaymentMethod\" AND isUp=\"1\"");
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
	
		$OrderStatus = " ";
		if($CartTotal > 0)
			$OrderStatus = "Preparing";
	
		mysqli_query($db_conn, "INSERT INTO order (MemberID, OrderSubtotal, OrderShipping, OrderTotal, OrderStatus, PaymentID, OrderPaymentName, OrderDate) VALUES ('$MemberID', '$CartSubtotal', '$ShippingFee', '$CartTotal', '$OrderStatus', '$PaymentMethod', '$PaymentName', NOW())");
		$OrderID = mysqli_insert_id($db_conn);
		
		$OrderNo = date("Ymd")."-".str_pad($OrderID, 5, "0", STR_PAD_LEFT); 
		mysqli_query($db_conn, "UPDATE order SET OrderNo='$OrderNo' WHERE OrderID='$OrderID'");
		
		$item_query = mysqli_query($db_conn, "SELECT * FROM cart_product WHERE CartID='$CartID'") ;
		while($item_row = mysqli_fetch_array($item_query)) 
		{	
			$CartProID = $item_row["CartProID"];
			$ProID = $item_row["ProID"];
			$ProName = addslashes($item_row["ProName"]);
			$ProPrice = $item_row["ProPrice"];
			$ProQty = $item_row["ProQty"];
			$ProTotal = $item_row["ProTotal"];

			$pro_query = mysqli_query($db_conn, "SELECT * FROM cart_product WHERE CartID='$CartID'");
			$pro_row = mysqli_fetch_array($pro_query);
			$ProUrl = $pro_row["ProUrl"];
			
			mysqli_query($db_conn, "INSERT INTO order_product (OrderID, ProID, ProName, ProUrl, ProPrice, ProQty, ProTotal) VALUES ('$OrderID', '$ProID', '$ProName','$ProUrl', '$ProPrice', '$ProQty', '$ProTotal')");
		}
		
		// address
		// mysqli_query($db_conn, "INSERT INTO js_store_order_address (OrderID, BillName, BillPhone, BillEmail, BillAdd, BillAdd2, BillPostcode, BillCity, BillState, BillCountry, ShipName, ShipPhone, ShipEmail, ShipAdd, ShipAdd2, ShipPostcode, ShipCity, ShipState, ShipCountry) VALUES ('$OrderID', '$BillName', '$BillPhone', '$BillEmail', '$BillAdd', '$BillAdd2', '$BillPostcode', '$BillCity', '$BillStateName', '$BillCountryName', '$ShipName', '$ShipPhone', '$ShipEmail', '$ShipAdd', '$ShipAdd2', '$ShipPostcode', '$ShipCity', '$ShipStateName', '$ShipCountryName')");
		$ship_query = mysqli_query($db_conn, "SELECT * FROM member_address WHERE MemberID='$MemberID' AND AddAddress!='' ORDER BY AddressAddDate DESC");
		$ship_num = mysqli_num_rows($ship_query);

		if($ship_num == "0")
			mysqli_query($db_conn, "INSERT INTO member_address (MemberID, AddName, AddPhone, AddAddress, AddPostcode, AddCity, AddState, AddCountry, AddressAddDate) VALUES ('$MemberID', '$ShipName', '$ShipPhone', '$ShipAdd', '$ShipPostcode', '$ShipCity', '$ShipState', '$ShipCountry', NOW())");

		if($ShipNew == "1")
		{
			mysqli_query($db_conn, "UPDATE member_address SET MemberID='$MemberID', AddName='$ShipName', AddPhone='$ShipPhone', AddAddress='$ShipAdd', AddPostcode='$ShipPostcode', AddCity='$ShipCity', AddState='$ShipState', AddCountry='$ShipCountry', AddressAddDate=NOW() WHERE MemberID='$MemberID'");
		}
		// END address
		
		
		// mysqli_query($db_conn, "INSERT INTO js_store_order_status (OrderID, StatusName, StatusDate) VALUES ('$OrderID', '$OrderStatus', NOW())");
		// $StatusID = mysqli_insert_id($db_conn);
			
				
		// delete from cart
		mysqli_query($db_conn, "DELETE FROM cart_product WHERE CartID='$CartID'");
		mysqli_query($db_conn, "DELETE FROM cart WHERE CartID='$CartID'");
		// END delete from cart
		
		// redirect to payment page
		// ORDER DETAIL
		$OrderAdd = stripslashes($ShipAdd).", ";
							
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
						
		$item_query = mysqli_query($db_conn, "SELECT * FROM order_product WHERE OrderID='$OrderID'");	  
		
		$OrderItems = "";
		$no=1;
		while ($item_row = mysqli_fetch_array($item_query)) 
		{	
			$ProID = $item_row["ProID"];
			$ProName = stripslashes($item_row["ProName"]);
			$ProPrice = $item_row["ProPrice"];
			$ProQty = $item_row["ProQty"];
			$ProTotal = $item_row["ProTotal"];
			
			$OrderItems .= "<br>".$no.". $ProName";
			
			$OrderDetail .= "<tr>
								<td width='80px' style='border-bottom:1px solid #DDD; padding:7px; padding-left:0px'>
									<img src='".$func->productPic($ProID)."' style='width:80px'>
								</td>
								<td style='border-bottom:1px solid #DDD; padding:7px; padding-right:0px'>
									<strong>".$ProName."</strong><br>
									<span style='display:block; color:#777; margin-bottom:10px; line-height:1.4; font-size:12px'>";

					$OrderDetail .= "</span>
										<span style='color:red'>".$func->formatNumber($ProPrice)."</span><br>
										Quantity: $ProQty";
					
			$OrderDetail .= "</td>
							</tr>";
			$no++;
		}				
						
		$OrderDetail .= "</table>";
		// END ORDER DETAIL
		
		$OrderAmount = "RM ".$CartTotal;
		
		// $custom_msg = $OrderNo."######".$StatusID."######".$OrderDetail."######".$OrderItems."######".$OrderAdd."######".$OrderAmount."######".stripslashes($OrderRemarks);
		$custom_msg = $OrderNo."######".$OrderID."######".$OrderDetail."######".$OrderItems."######".$OrderAdd."######".$OrderAmount."######".stripslashes($OrderRemarks);
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