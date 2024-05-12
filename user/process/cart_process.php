<?php
require_once("../lib/function.php");

$func = new Functions;

if(isset($_POST["BtnAdd"]))
{
	$ProductID = $_POST["ProID"];
	$quantity = "1";
	$type = $_POST["type"];
	$ProSize = $_POST["ProSize"];
	$ProColor = $_POST["ProColor"];
	$ProductPrice = $_POST["ProFinalPrice"];
	$TotalPrice = $ProductPrice * $quantity;

	// product
	$name_query = mysqli_query($db_conn, "SELECT OptSize, ProName, LuggageSmallName, LuggageMediumName, LuggageLargeName, LuggageSet3Name FROM js_store_luggage WHERE LuggageID='$ProductID'");
	$name_row = mysqli_fetch_array($name_query);
	
	$OptSize = $name_row["OptSize"];
	$ProductName = $name_row["ProName"];
	
	if($OptSize == 1)
	{
		switch($ProSize)
		{
			case "small": $ProductName = (!empty($name_row["LuggageSmallName"]) ? $name_row["LuggageSmallName"] : ""); break;
			case "medium": $ProductName = (!empty($name_row["LuggageMediumName"]) ? $name_row["LuggageMediumName"] : ""); break;
			case "large": $ProductName = (!empty($name_row["LuggageLargeName"]) ? $name_row["LuggageLargeName"] : ""); break;
			case "set3": $ProductName = (!empty($name_row["LuggageSet3Name"]) ? $name_row["LuggageSet3Name"] : ""); break;
		}
	}
	
	$color_query = mysqli_query($db_conn, "SELECT ColorName FROM js_store_luggage_color WHERE ColorID='$ProColor'");
	$color_row = mysqli_fetch_array($color_query);
	$ColorName = stripslashes($color_row["ColorName"]);
	
	$ProVarID = ucwords($ProSize);
	if($ProSize == "set3")
		$ProVarID = "Set of Three";
	
	$ProVarName = $ColorName;
	// END product
	
	$CartSql = "";
	if($UserID > 0)
		$CartSql = " OR UserID='$UserID'";
		
	$cart_query = mysqli_query($db_conn, "SELECT CartID FROM js_store_cart WHERE (CartSession='".$CurrCart."'".$CartSql.")");
	$cart_num = mysqli_num_rows($cart_query);
	
	if($cart_num == 0)
	{
		mysqli_query($db_conn, "INSERT INTO js_store_cart (CartSession, UserID, strAddDate) VALUES ('$CurrCart', '$UserID', DATE_ADD(NOW(), INTERVAL 8 HOUR))");
		$CartID = mysqli_insert_id($db_conn);
	}
	else
	{
		$cart_row = mysqli_fetch_array($cart_query);
		$CartID = $cart_row["CartID"];
	}
	
	$pro_query = mysqli_query($db_conn, "SELECT CartProID, ProQty FROM js_store_cart_products WHERE CartID='$CartID' AND ProID='$ProductID' AND ProSku='$type' AND ProVarID='$ProVarID' AND ColorID='$ProColor'");
	$pro_num = mysqli_num_rows($pro_query);
	
	if($pro_num == 0)
	{
		mysqli_query($db_conn, "INSERT INTO js_store_cart_products (CartID, ProID, ProName, ProSku, ProVarID, ProVarName, ColorID, ProPrice, ProQty, ProTotal, strAddDate) VALUES ('$CartID', '$ProductID', '$ProductName', '$type', '$ProVarID', '$ProVarName', '$ProColor', '$ProductPrice', '$quantity', '$TotalPrice', DATE_ADD(NOW(), INTERVAL 8 HOUR))");
		$CartProID = mysqli_insert_id($db_conn);
	}
	else
	{
		$pro_row = mysqli_fetch_array($pro_query);
		
		$CartProID = $pro_row["CartProID"];
		$ProQty = $pro_row["ProQty"];
		$quantity = $ProQty + $quantity;
		$TotalPrice = $ProductPrice * $quantity;
		
		mysqli_query($db_conn, "UPDATE js_store_cart_products SET ProName='$ProductName', ProSku='$type', ProVarID='$ProVarID', ProVarName='$ProVarName', ProPrice='$ProductPrice', ProQty='$quantity', ProTotal='$TotalPrice', strModifyDate=DATE_ADD(NOW(), INTERVAL 8 HOUR) WHERE CartProID='$CartProID'");
	}
	
	$func->updateCartTotal($CartID);
	
	echo "<script>self.location='$SiteUrl/cart/'</script>";
}
?>