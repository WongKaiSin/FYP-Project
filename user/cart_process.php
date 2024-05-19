<?php
session_start();
require_once("./lib/db.php");
require_once("./lib/function.php");

$SiteUrl = "http://localhost:80/FYP-Project";
$func = new Functions;
$CurrCart = $_SESSION["Cart"];
$MemberID = $_SESSION["MemberID"];

if(isset($_POST["BtnAdd"]))
{
	// $ProductID = $_POST["ProID"];
	// $quantity = "1";
    // $type = $_POST["type"];
	// $ProSize = $_POST["ProSize"];
	// $ProColor = $_POST["ProColor"];
	// $ProductPrice = $_POST["ProFinalPrice"];
	// $TotalPrice = $ProductPrice * $quantity;

    $ProID = $_POST["ProID"];
    $ProQty = $_POST["ProQty"];
    $ProName = $_POST["ProName"];
    $ProUrl = $_POST["ProUrl"];

	// Get product info
	$info_query = mysqli_query($db_conn, "SELECT `ProPrice`, `ProStock` FROM product WHERE `ProID`='$ProID'");
	$info_row = mysqli_fetch_array($info_query);
	
	$ProPrice = $info_row["ProPrice"];
	$ProStock = $info_row["ProStock"];

    $TotalPrice = $ProPrice * $ProQty;
		
	$CartSql = "";
	if(empty($MemberID))
    {
        echo "<script>
                alert('Please sign up and add to cart');
                self.location = '$SiteUrl/user/registration.php'
            </script>";
    }
	
    // Check cart
	$cart_query = mysqli_query($db_conn, "SELECT CartID FROM cart WHERE (CartSession='".$CurrCart."' OR MemberID='$MemberID')");
	$cart_num = mysqli_num_rows($cart_query);
	
	if($cart_num == 0)
	{
		mysqli_query($db_conn, "INSERT INTO cart (`CartSession`, `MemberID`, `CartAddDate`) VALUES ('$CurrCart', '$MemberID', NOW())");
        $CartID = mysqli_insert_id($db_conn);
	}
	else
	{
		$cart_row = mysqli_fetch_array($cart_query);
		$CartID = $cart_row["CartID"];
	}
	
	$pro_query = mysqli_query($db_conn, "SELECT * FROM cart_product WHERE `CartID`='$CartID' AND `ProID`='$ProID'");
	$pro_num = mysqli_num_rows($pro_query);
	
	if($pro_num == 0)
	{
		mysqli_query($db_conn, "INSERT INTO cart_product (`CartID`, `ProID`, `ProPrice`, `ProQty`, `ProTotal`, `ProAddDate`) VALUES ('$CartID', '$ProID', '$ProPrice', '$ProQty', '$TotalPrice', NOW())");
		$CartProID = mysqli_insert_id($db_conn);
	}
	else
	{
		$pro_row = mysqli_fetch_array($pro_query);
		$CartProID = $pro_row["CartProID"];
		
		mysqli_query($db_conn, "UPDATE cart_product SET `ProPrice`='$ProPrice', `ProQty`='$ProQty', `ProTotal`='$TotalPrice', `ProAddDate`=NOW() WHERE `CartProID`='$CartProID'");
	}
	
	$func->updateCartTotal($CartID);
	
	echo "<script>self.location='$SiteUrl/user/cart.php'</script>";
}
?>