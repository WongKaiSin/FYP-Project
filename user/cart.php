<?php
include("lib/head.php");
require_once("lib/db.php");
require_once("lib/function.php");

$msg = (isset($_GET['msg']) ? $_GET["msg"] : "");
$SiteUrl = "http://localhost:80/FYP-Project";
$no = 1;	
$NoStock = 0;

$func = new Functions;

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
	$CartSubtotal = $cart_row["CartSubTotal"];
	$CartTotal = $cart_row["CartTotal"];

    $item_query = mysqli_query($db_conn, "SELECT * FROM cart_product WHERE `CartID`='$CartID'") ;
	$item_num = mysqli_num_rows($item_query);

    if($item_num == 0)
	{
		$cart .= "Your shopping cart is empty.<br><br>
				  <button type='button' class=\"button is-outline\" onclick=\"document.location='$SiteUrl/user/menu.php'\"><i class=\"fa fa-arrow-left\"></i>Continue Shopping</button><br><br>";
	}
	else
    {
		$cart .= $func->checkoutStep(1);

        if(!empty($msg))
		{
			switch($msg)
			{
				case "updated": $msg_text = "The cart is updated."; $type='success'; break;
				case "deleted": $msg_text = "The product is removed from cart."; $type='success'; break;
			}
            $cart .= $func->displayMsg($type, $msg_text);
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
        <form action="cart_process.php" method="post">
            <table class="table-listing table-item-delete">
                <thead>
                    <tr>
                        <th colspan='2'>Product</th>
                        <th class='text-right'>Price (RM)</th>
                        <th class='text-right'>Quantity</th>
                        <th class='text-right'>Total (RM)</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    while($item_row = $item_query->fetch_array())
                    {
                        $CartProID = $item_row["CartProID"];
                        $ProID = $item_row["ProID"];
                        $ProPrice = $item_row["ProPrice"];
                        $ProQty = $item_row["ProQty"];
                        $ProTotal = $item_row["ProTotal"];

                        $pro_query = $db_conn -> query("SELECT ProName, ProUrl, ProStock FROM product WHERE ProID=$ProID");
                        $pro_row = $pro_query->fetch_array();
                        $ProName = $pro_row["ProName"];
                        $ProUrl = $pro_row["ProUrl"];
                        $ProStock = $pro_row["ProStock"];

                        $StockText = "";
                        if($ProStock < $ProQty)
						{
							$StockText = "<br><strong style='color:red'>Out of Stock ".($ProStock > 0 ? "(Available Quantity: ".$ProStock.")" : "")."</strong>";
							$NoStock = "1";
						}

                        echo "<tr>
                            <td><img src='".$func->productPic($ProID)."' class='img-fluid'></td>
                            <td>
                                <a style='cursor:default'><strong><?=$ProName?></strong></a><br>
                                <span class='text-small'></span>
                                <div class='listing-remarks-box'>
                                    Remarks:<br>
                                     <textarea name='remarks[$CartProID]'></textarea>
                                </div>
                            </td>
                            <td class='text-right'>
                                <span class='d-lg-none'><strong>Price:</strong> RM</span>
                                ".$ProPrice."
                            </td>
                            <td class='text-right'>
                                <span class='d-lg-none'><strong>Quantity:</strong></span>
                                <div class='quantity-box'>
                                    <button type='button' class='button minus' value='-' data-rel='".$CartProID."'><i class='fa fa-minus'></i></button>
                                    <input type='text' name='ProQty[".$CartProID."]' value='".$ProQty."' id='qty-box".$CartProID."' data-max='".$ProStock."' style='font-size: 15px;'>
                                    <button type='button' class='button plus' value='+' data-rel='".$CartProID."'><i class='fa fa-plus'></i></button>
                                </div>
                                <input type='hidden' name='CartProID[]' value='".$CartProID."'>
                            </td>
                            <td class='text-right'>
                                <span class='d-lg-none'><strong>Total:</strong> </span>
                                ".$ProTotal."
                            </td>
                            <td class='text-right'>
                                <a href='$SiteUrl/user/cart-process.php?action=Delete&CartProID=$CartProID' class='tooltip' data-title='Remove'>
                                    <i class='fa fa-trash-o'  style='font-size:48px;color:red'></i>
                                    <span class='d-lg-none'>Delete</span>
                                </a>
                            </td>
                        </tr>";
                        $no++;
                    }
                ?>
                </tbody>
            </table>
            <div class="row button-box">
                <div class="col small-12 medium-6 large-6">
                    <a href="<?=$SiteUrl?>/user/menu.php">
                        <button type="button" name="BtnCont" class="button is-outline pull-left"><i class="fa fa-arrow-left"></i>Continue Shopping</button>
                    </a>
                </div>
                <div class="col small-12 medium-6 large-6">
                    <button type="submit" name="BtnUpdate" class="button pull-right">Update Cart</button>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col large-12">
                <div class="pull-right total-box">
                    <table class="table-totals">
                        <thead>
                            <tr>
                                <th colspan='2'>Cart Totals (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Subtotal</td>
                                <td><?=$CartSubtotal?></td>
                            </tr>
                            <tr>
                                <td><strong>Total</strong></td>
                                <td><?=$CartTotal?></td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                    if($NoStock == 0)			  
                        echo "<a href='checkout.php' class='button width-100'>Proceed to Checkout</a>";
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

<script>
    $(document).ready(function() {
    $('.minus').click(function () {
        var $input = $(this).siblings('input');
        var count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.change();
        return false;
    });

    $('.plus').click(function () {
        var $input = $(this).siblings('input');
        var value = (parseInt($input.val()) + 1);
        var max = parseInt($input.attr('data-max'));

        if (value > max && max != "limit") {
            alert('Only have ' + addCommas(max) + ' units available.');
            value = max;
        }
    });

    
    $(document).on("change", ".attribute", function() {
        var variations = "";
        var ProID = $("#ProID").val();
        var count = 0,
            selected = 0;

        $("#bg-overlay").addClass("loading").fadeIn();
        $(".attribute").each(function() {
            if ($(this).val() != "") {
                selected++;
            }

            count++;
        });

        $(".attribute option:selected").each(function() {
            variations += $(this).val().replace("#", "-");
        });

        $("#BtnSelect").hide();
        $("#BtnOutStock").hide();

        var result = $.ajax({
            type: "GET",
            url: domain + "/user/lib/ajax.php?action=stock&ProID=" + ProID,
            async: false,
        }).responseText; {
            var data = $.parseJSON(result);

            if (result != "") {
                var ProStock = data["ProStock"];

                if (ProStock > 0) // if have stock
                {
                    if ($("#qty-box" + ProID).val() > ProStock) {
                        $("#qty-box" + ProID).val(ProStock);
                    }
                    $(".product-info-stock").show();
                    $("#qty-box" + ProID).attr("data-max", ProStock);
                    $("#BtnCart").show();
                    $("#BtnOutStock").hide();
                } else {
                    $(".product-info-stock").hide();
                    $("#BtnCart").hide();

                    if (count == selected) {
                        $("#BtnOutStock").show();
                    }
                }
            } else // show out of stock if empty result
            {
                $(".product-info-stock").hide();
                $("#BtnCart").hide();
                $("#BtnOutStock").show();
            }
        }

        setTimeout(function() {
            $("#bg-overlay").removeClass("loading").fadeOut();
        }, 200)
    });


    /*** product quantity control ***/
    $(document).on("click", ".quantity-box > button", function() {
        var id = $(this).attr("data-rel");
        var qty = parseInt($("#qty-box" + id).val());
        var QtyMax = parseInt($("#qty-box" + id).attr("data-max"));
        var action = $(this).val();
        var NewQty = 0;

        if (action == "-") {
            NewQty = qty - 1;
        } else if (action == "+") {
            NewQty = qty + 1;
        }

        if (NewQty <= 0) {
            NewQty = 1;
        }

        if (NewQty > QtyMax && QtyMax != "limit") {
            $("#bg-overlay").fadeIn();
            $("#popup-alert-text").html('Only have ' + addCommas(QtyMax) + ' units available.');
            $("#popup-alert-box").fadeIn();
            NewQty = QtyMax;
        }

        $("#qty-box" + id).val(NewQty);
    });

    $(document).on('keyup', 'input[name^=ProQty]', function() {
        var qty = $(this).val();
        var QtyMax = parseInt($(this).attr("data-max"));

        if (isNaN(qty) || qty <= 0) {
            $(this).val(1);
        }

        if (qty > QtyMax && QtyMax != "limit") {
            $("#bg-overlay").fadeIn();
            $("#popup-alert-text").html('Only have ' + addCommas(QtyMax) + ' units available.');
            $("#popup-alert-box").fadeIn();
            $(this).val(QtyMax);
        }
    });
    /*** END product quantity control ***/
});

</script>
</html>