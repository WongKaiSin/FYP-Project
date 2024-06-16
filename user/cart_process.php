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
    $ProID = $_POST["ProID"];
    $ProQty = $_POST["ProQty"];
    $ProName = $_POST["ProName"];
    $ProUrl = $_POST["ProUrl"];

	// Get product quantity from cart_product
	$qty_query = mysqli_query($db_conn, "SELECT `ProQty` FROM cart_product WHERE `ProID`='$ProID'");
	$qty_row = mysqli_fetch_array($qty_query);

	if($qty_row == 1)
		$OldQty = $qty_row["ProQty"];
	else
		$OldQty = 0;

	// Get product info
	$info_query = mysqli_query($db_conn, "SELECT `ProPrice`, `ProStock` FROM product WHERE `ProID`='$ProID'");
	$info_row = mysqli_fetch_array($info_query);
	
	$ProPrice = $info_row["ProPrice"];
	$ProStock = $info_row["ProStock"];

	$NewQty = $OldQty + $ProQty;
    $TotalPrice = $ProPrice * $NewQty;
		
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
		mysqli_query($db_conn, "INSERT INTO cart_product (`CartID`, `ProID`, `ProPrice`, `ProQty`, `ProTotal`, `ProAddDate`) VALUES ('$CartID', '$ProID', '$ProPrice', '$NewQty', '$TotalPrice', NOW())");
		$CartProID = mysqli_insert_id($db_conn);
	}
	else
	{
		$pro_row = mysqli_fetch_array($pro_query);
		$CartProID = $pro_row["CartProID"];
		
		mysqli_query($db_conn, "UPDATE cart_product SET `ProPrice`='$ProPrice', `ProQty`='$NewQty', `ProTotal`='$TotalPrice', `ProAddDate`=NOW() WHERE `CartProID`='$CartProID'");
	}
	
	$func->updateCartTotal($CartID);
	
	echo "<script>self.location='$SiteUrl/user/cart.php'</script>";
}

if(isset($_POST["BtnUpdate"]))
{
	$CartProID = $_POST["CartProID"];

	foreach ($CartProID as $id)
	{
		$ProQty = $_POST["ProQty"][$id];

		$cart_query = mysqli_query($db_conn, "SELECT CartID, ProPrice FROM cart_product WHERE CartProID='$id'");
		$cart_row = mysqli_fetch_array($cart_query);
		
		$CartID = $cart_row["CartID"];
		$ProPrice = $cart_row["ProPrice"];
		$ProTotal = $ProPrice * $ProQty;

		mysqli_query($db_conn, "UPDATE cart_product SET ProQty='$ProQty', ProTotal='$ProTotal', ProAddDate=NOW() WHERE CartProID='$id'");
	}
	
	$func->updateCartTotal($CartID);
	
	echo "<script>self.location='$SiteUrl/user/cart.php'</script>";
}

if($_GET["action"] == "Delete" && $_GET["CartProID"] > 0)
{
	$CartProID = $_GET["CartProID"];
	
	$cart_query = mysqli_query($db_conn, "SELECT `CartID` FROM cart_product WHERE `CartProID`='$CartProID'");
	$cart_row = mysqli_fetch_array($cart_query);
	
	mysqli_query($db_conn, "DELETE FROM cart_product WHERE `CartProID`='$CartProID'");
	
	$func->updateCartTotal($cart_row["CartID"]);
	
	echo "<script>self.location='$SiteUrl/user/cart.php'</script>";
}


?>