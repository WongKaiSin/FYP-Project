<?php
include("lib/head.php");
require_once("lib/db.php");

$msg = (isset($_GET['msg']) ? $_GET["msg"] : "");
$SiteUrl = "http://localhost:80/FYP-Project";

$MemberID = $_SESSION["MemberID"];
$CurrCart = $_SESSION["Cart"];

$cart_query = mysqli_query($db_conn, "SELECT * FROM cart WHERE (`CartSession`='".$CurrCart."' OR `MemberID`='$MemberID') ORDER BY `CartID` DESC");
$cart_num = mysqli_num_rows($cart_query);


if($cart_num == 0)
{
	$cart .= "Your shopping cart is empty.<br><br>
			  <button type='button' class=\"button is-outline\" onclick=\"document.location='$SiteUrl/user/menu.php'\"><i class=\"fa fa-arrow-left\"></i>Continue Shopping</button><br><br>";
}
else
{
    $cart_row = mysqli_fetch_array($cart_query);
	
	$CartID = $cart_row["CartID"];
	$CartSubtotal = $cart_row["CartSubtotal"];
	$CartTotal = $cart_row["CartTotal"];

    $item_query = mysqli_query($db_conn, "SELECT * FROM cart_products WHERE `CartID`='$CartID'") ;
	$item_num = mysqli_num_rows($item_query);

    if($item_num == 0)
	{
		$cart .= "Your shopping cart is empty.<br><br>
				  <button type='button' class=\"button is-outline\" onclick=\"document.location='$SiteUrl/user/menu.php'\"><i class=\"fa fa-arrow-left\"></i>Continue Shopping</button><br><br>";
	}
	else
    {
		$cart .= checkoutStep(1);

        if(!empty($msg))
		{
			switch($msg)
			{
				case "updated": $msg_text = "The cart is updated."; $type='success'; break;
				case "deleted": $msg_text = "The product is removed from cart."; $type='success'; break;
				case "coupon-applied": $msg_text = "The coupon is already applied."; $type="success"; break;
				case "referral-applied": $msg_text = "The referral code is already applied."; $type="success"; break;
				case "credit-applied": $msg_text = "The credits is already applied."; $type="success"; break;
				case "credit-removed": $msg_text = "The credits is already removed."; $type="success"; break;
				case "credit-invalid": $msg_text = "The credits amount applied is more than available credits."; $type="error"; break;
			}
            $cart .= displayMsg($type, $msg_text);
        }

    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Cart</title>
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
          <h2>Cart</h2>
        </div>
      </div>
    </div><!-- End Breadcrumbs -->

    <section class="sample-page">
      <div class="container" data-aos="fade-up">

        <!--  Form to cart-process  -->
        <form action="$SiteUrl/cart-process/" method="post">
            <table class="table-listing table-item-delete">
                <thead>
                    <tr>
                        <th colspan='2'>$PRODUCT_VIEWCART</th>
                        <th class='text-right'>$PRICE_VIEWCART ($CurrCode)</th>
                        <th class='text-right'>Quantity</th>
                        <th class='text-right'>$TOTAL_VIEWCART ($CurrCode)</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><img src='".productPic($ProVarID, $ProVarName, "S")."'></td>
                        <td>
                            <a style='cursor:default'><strong>".$ProName."</strong></a><br>
                            <span class='text-small'></span>
                            <div class="listing-remarks-box">
                                Remarks:<br>
                                <textarea name='remarks[$CartProID]'>".$ProRemarks."</textarea>
                            </div>
                        </td>
                        <td class='text-right'>
                            <span class='hide-for-large'><strong>$PRICE_VIEWCART:</strong> $CurrCode</span>
                            ".currConvert($ProPrice)."
                        </td>
                        <td class='text-right'>
                            <span class='hide-for-large'><strong>Quantity:</strong></span>
                            <div class="quantity-box">
                                <button type="button" class="button minus" value="-" data-rel="<?=$CartProID ?>"><i class="fa fa-minus"></i></button>
                                <input type="text" name="quantity[$CartProID]" id="box-<?=$CartProID?>" class="quantity" value="<?=$ProQty?>" style="font-size: 15px;">
                                <button type="button" class="button plus" value="+" data-rel="<?=$CartProID?>"><i class="fa fa-plus"></i></button>
                            </div>
                            <input type='hidden' name='CartProID[]' value='".$CartProID."'>
                        </td>
                        <td class='text-right'>
                            <span class='hide-for-large'><strong>$TOTAL_VIEWCART:</strong> $CurrCode</span>
                            ".currConvert($ProTotal)."
                        </td>
                        <td class='text-right'>
                            <a href="$SiteUrl/cart-process/Delete/$CartProID/" class="tooltip" data-title="$REMOVE_VIEWCART">
                                <i class="fa fa-trash"></i>
                                <span class='hide-for-large'>Delete</span>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="row button-box">
                <div class="col small-12 medium-6 large-6">
                    <a href="$SiteUrl/<?=PRODUCTS_PREFIX?>/">
                        <button type="button" name="BtnCont" class="button is-outline pull-left" /><i class="fa fa-arrow-left"></i>Continue Shopping</button>
                    </a>
                </div>
                <div class="col small-12 medium-6 large-6">
                    <button type="submit" name="BtnUpdate" class="button pull-right" />Update Cart</button>
                </div>
            </div>
        </form>
        <div class="row">
            <div class=\"col large-12\">
                <div class=\"pull-right total-box\">
                    <table class=\"table-totals\">
                        <thead>
                            <tr>
                                <th colspan='2'>Cart Totals ($CurrCode)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>$SUBTOTAL_VIEWCART</td>
                                <td>".currConvert($CartSubtotal)."</td>
                            </tr>
                            <tr>
                                <td><strong>$TOTAL_VIEWCART</strong></td>
                                <td>".currConvert($CartTotal)."</td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                    if($NoStock == 0)			  
                        echo "<a href=\"$SiteUrl/checkout/\" class=\"button width-100\">Proceed to Checkout</a>";
                    else
                        echo "<strong style='color:red'>One or more product is out of stock, please remove the product to proceed.</strong>";
                    ?>
				</div>
            </div>
        </div>
        <!--  END Form to cart-process  -->

      </div>
    </section>

  </main><!-- End #main -->
  <?php include("lib/footer.php"); ?>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

</body>
</html>