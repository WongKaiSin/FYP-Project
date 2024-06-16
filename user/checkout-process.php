<?php
session_start();

require_once("lib/db.php");
require_once("lib/function.php");

$SiteUrl = "http://localhost:80/FYP-Project";
$func = new Functions;

$MemberID = $_SESSION["MemberID"];

if(isset($_POST["Checkout"]) && $_POST["Checkout"] == "1")
{
	$ShippingFee = $_POST["ShippingFee"];
	$checkout = $func->checkCart($ShippingFee);
	
	$cart_query = mysqli_query($db_conn, "SELECT * FROM cart WHERE MemberID='$MemberID' ORDER BY CartID DESC");
	$cart_num = mysqli_num_rows($cart_query);

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

	if($CartSubtotal > 0)
	{
		echo "<p class='text-center'>
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
		$orderType = $_POST["orderType"];
		
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
			$PaymentResponse = $payment_row["PaymentResponse"];
		}
	
		$OrderStatus = " ";
		if($CartTotal > 0)
			$OrderStatus = "Preparing";
	
		// Prepare the statement
		$stmt = $db_conn->prepare("INSERT INTO `order` (`MemberID`, `OrderType`, `OrderSubtotal`, `OrderShipping`, `OrderTotal`, `OrderStatus`, `PaymentID`, `OrderDate`) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
		$stmt->bind_param("iidddsi", $MemberID, $orderType, $CartSubtotal, $ShippingFee, $CartTotal, $OrderStatus, $PaymentMethod);
		$stmt->execute();
		$stmt->close();

		$OrderID = mysqli_insert_id($db_conn);
		
		$OrderNo = date("Ymd")."-".str_pad($OrderID, 5, "0", STR_PAD_LEFT); 
		mysqli_query($db_conn, "UPDATE `order` SET `OrderNo`='$OrderNo' WHERE `OrderID`='$OrderID'");
		
		// $item_query = mysqli_query($db_conn, "SELECT * FROM cart_product WHERE CartID='$CartID'") ;
		$stmt = $db_conn->prepare("SELECT * FROM cart_product WHERE CartID = ?");
		$stmt->bind_param("i", $CartID);
		$stmt->execute();
		$result = $stmt->get_result();

		// while($item_row = mysqli_fetch_array($item_query)) 
		while($item_row = $result->fetch_assoc())
		{	
			$CartProID = $item_row["CartProID"];
			$ProID = $item_row["ProID"];
			$ProPrice = $item_row["ProPrice"];
			$ProQty = $item_row["ProQty"];
			$ProTotal = $item_row["ProTotal"];

			// $pro_query = mysqli_query($db_conn, "SELECT `ProStock`, `ProUrl`, `ProName` FROM `product` WHERE `ProID`=`$ProID`");
			// $pro_row = mysqli_fetch_array($pro_query);
			// $ProUrl = $pro_row["ProUrl"];
			// $ProName = addslashes($pro_row["ProName"]);
			// $OldStock = $pro_row["ProStock"];

			$stmt = $db_conn->prepare("SELECT ProStock, ProUrl, ProName FROM product WHERE ProID = ?");
			$stmt->bind_param("i", $ProID);
			$stmt->execute();
			$stmt->bind_result($OldStock, $ProUrl, $ProName);
			$stmt->fetch();
			$stmt->close();
			
			// mysqli_query($db_conn, "INSERT INTO order_product (OrderID, ProID, ProName, ProUrl, ProPrice, ProQty, ProTotal) VALUES ('$OrderID', '$ProID', '$ProName','$ProUrl', '$ProPrice', '$ProQty', '$ProTotal')");
			
			$stmt = $db_conn->prepare("INSERT INTO order_product (OrderID, ProID, ProName, ProUrl, ProPrice, ProQty, ProTotal) VALUES (?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("iissdid", $OrderID, $ProID, $ProName, $ProUrl, $ProPrice, $ProQty, $ProTotal);
			$stmt->execute();
			$stmt->close();

			// Update stock
			$NewStock = $OldStock-$ProQty;

			$stmt = $db_conn->prepare("UPDATE product SET ProStock = ? WHERE ProID = ?");
			$stmt->bind_param("ii", $NewStock, $ProID);
			$stmt->execute();
			$stmt->close();
		}
		
		// address
		$ship_query = mysqli_query($db_conn, "SELECT * FROM member_address WHERE MemberID='$MemberID' AND AddAddress!='' ORDER BY AddressAddDate DESC");
		$ship_num = mysqli_num_rows($ship_query);

		if($ship_num == "0")
			mysqli_query($db_conn, "INSERT INTO member_address (MemberID, AddName, AddPhone, AddAddress, AddPostcode, AddCity, AddState, AddCountry, AddressAddDate) VALUES ('$MemberID', '$ShipName', '$ShipPhone', '$ShipAdd', '$ShipPostcode', '$ShipCity', '$ShipState', '$ShipCountry', NOW())");

		mysqli_query($db_conn, "UPDATE member_address SET MemberID='$MemberID', AddName='$ShipName', AddPhone='$ShipPhone', AddAddress='$ShipAdd', AddPostcode='$ShipPostcode', AddCity='$ShipCity', AddState='$ShipState', AddCountry='$ShipCountry', AddressAddDate=NOW() WHERE MemberID='$MemberID'");
		// END address
			
				
		// delete from cart
		// mysqli_query($db_conn, "DELETE FROM cart_product WHERE CartID='$CartID'");
		// mysqli_query($db_conn, "DELETE FROM cart WHERE CartID='$CartID'");

		$stmt1 = $db_conn->prepare("DELETE FROM cart_product WHERE CartID = ?");
		$stmt1->bind_param("i", $CartID);
		$stmt1->execute();
		$stmt1->close();

		// Prepare and execute delete statement for cart
		$stmt2 = $db_conn->prepare("DELETE FROM cart WHERE CartID = ?");
		$stmt2->bind_param("i", $CartID);
		$stmt2->execute();
		$stmt2->close();
		// END delete from cart
		

		// For delivery
		if($orderType == 0)
		{
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
			$custom_msg = $OrderNo."######".$OrderID."######".$OrderDetail."######".$OrderItems."######".$OrderAdd."######".$OrderAmount;
		}
		// End delivery send email


		// redirect to payment page
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
				echo $payment_form.
								"<script type='text/javascript'>
									$(document).ready(function()
									{
										$('#PaymentForm').submit();
									});
								</script>";
			}
			else
				echo "<script>self.location='$SiteUrl/user/member_order.php?OrderID=$OrderID&msg=complete'</script>";
		}
		else
		{
			echo "<script>self.location='$SiteUrl/user/member_order.php?OrderID=$OrderID&msg=complete'</script>";
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
	echo "<script>
					  alert('An error has occurred during the transaction. Please try again.');
					  self.location='cart.php'
				  </script>";
}

?>