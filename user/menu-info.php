<?php
require_once("lib/db.php");
require_once("lib/function.php");

$func = new Functions;

$SiteUrl = "http://localhost:80/FYP-Project";
$VarHide = "";
$img_lay = "";

if(isset($_GET["ProUrl"]))
  $ProUrl = $_GET["ProUrl"];

$pro_sql = "SELECT * FROM product WHERE `ProUrl` = '".$ProUrl."'";
$pro_query = $db_conn ->query($pro_sql);

if($pro_query->num_rows > 0)
{
  $VarHide = " hide";
  $row = $pro_query->fetch_assoc();

  $ProID = $row["ProID"];
  $ProName = $row["ProName"];
  $ProPrice = $row["ProPrice"];
  $ProDesc = $row["ProDesc"];
  $Ingre = $row["Ingredient"];
  $store = $row["Storage"];
  $life = $row["ShelfLife"];
  $ProStock = $row["ProStock"];

  // Category
  $cat_sql = "SELECT CatName FROM product_cat WHERE ProID = ".$ProID."";
  $cat_query = $db_conn ->query($cat_sql);

  $cat_row = $cat_query->fetch_assoc();
  $CatName = $cat_row["CatName"];
  // End Category

  // For Image
  $img_sql = "SELECT `ImageName`, `ImageExt` FROM product_image WHERE `ProID` = ".$ProID."";
  $img_query = $db_conn ->query($img_sql);

  $img_num = $img_query->num_rows;

  $img_lay ="<div class='cont'>";

  if($img_num > 0)
  {
    if($img_num > 1)
    {
      $j=1;
      while($img_row = $img_query->fetch_assoc())
      {
        $ImageName = $img_row["ImageName"];
        $ImageExt = $img_row["ImageExt"];

        $img_lay .="<div class='mySlides'>
                      <div class='numbertext'>".$j." / ".$img_num."</div>
                      <img src='$SiteUrl/upload/product/$ProID/$ImageName.$ImageExt' style='width:100%'>
                    </div>";
        $j++;
      }
        $img_lay .="<!-- Next and previous buttons -->
                    <a class='prev' onclick='plusSlides(-1)'>&#10094;</a>
                    <a class='next' onclick='plusSlides(1)'>&#10095;</a>
                  
                    <!-- Thumbnail images -->
                    <div class='slide-rw'>";

      // Reset the query to fetch images again
      $img_query->data_seek(0);
      $j=1;
      while($img_row = $img_query->fetch_assoc())
      {
        $ImageName = $img_row["ImageName"];
        $ImageExt = $img_row["ImageExt"];

          $img_lay .="<div class='slide-clmn'>
                        <img class='demo cur' src='$SiteUrl/upload/product/$ProID/$ImageName.$ImageExt' style='width:100%' onclick='currentSlide(".$j.")'>
                      </div>";
        $j++;
      }
      $img_lay .="</div>";
    }
    else
    {
      while($img_row = $img_query->fetch_assoc())
      {
        $ImageName = $img_row["ImageName"];
        $ImageExt = $img_row["ImageExt"];

        $img_lay .= " <a href='$SiteUrl/upload/product/$ProID/$ImageName.$ImageExt'>
                        <img src='$SiteUrl/upload/product/$ProID/$ImageName.$ImageExt' style='width:100%'>
                      </a>";
      }
    }
  }
  else
  {
    $img_lay .="<img src='$SiteUrl/user/assets/img/no-image.png' style='width:100%'>";
  }
  $img_lay .="</div>";
  // End Image

  // For Rating
  $avg_rating_query = "SELECT AVG(RevRate) AS avg_rate FROM review_rate WHERE ProID = $ProID";
  $avg_rating_result = $db_conn->query($avg_rating_query);
  // End Rating
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("lib/head.php"); ?>
  <title><?=$ProName // food name?> - LBM</title>
  <style>
 
  </style>
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
          <h2><?=$ProName?></h2>
          <ol>
            <li><a href="index.php">Home</a></li>
            <li><a href="menu.php?CatID=<?=urlencode($CatName);?>"><?=$CatName; ?></a></li>
          </ol>
        </div>
      </div>
    </div>
    <!-- End Breadcrumbs -->

    <section class="sample-page">
      <div class="container" data-aos="fade-up">
        <div class="row product-info-row" style="margin-bottom:10px">
          <div class="col-md-6">
            <?=$img_lay; ?>
          </div>
          <div class="col-md-6">
            <div class="product-info-side">
              <aside>
                <h3><?=$ProName?></h3>
                  <?php
                    if ($avg_rating_result && $avg_rating_row = $avg_rating_result->fetch_assoc()) {
                        echo '<div class="star-rating-container">';
                        echo $func->displayStars($avg_rating_row['avg_rate']);
                        echo '</div>';
                    } else {
                        echo '<span>No rating available</span>';
                    }
                  ?>

                <div class="product-info-price">RM <?=$ProPrice?></div>

                <!--This is the form to cart-->
                <form action="cart_process.php" name="cart" id="cart" method="POST">
                  <div class="row">
                    <div class="col-sm-5 product-info-stock <?=($ProStock <= 0 ? ' hide' : '')?>">
                      <div class="quantity-box">
                        <button type="button" class="button minus" value="-" data-rel="<?=$ProID?>">-</button>
                        <input type="text" name="ProQty" value="1" id="qty-box<?=$ProID?>" data-max="<?=$ProStock?>">
                        <button type="button" class="button plus" value="+" data-rel="<?=$ProID?>">+</button>
                      </div>
                    </div>
                    <div class="col-sm-7 product-info-stock <?=($ProStock <= 0 ? ' hide' : '')?>">
                      <div class="product-info-button">
                          <input type="hidden" name="ProID" id="ProID" value="<?=$ProID?>">
                          <input type="hidden" name="ProName" value="<?=$ProName?>">
                          <input type="hidden" name="ProUrl" value="<?=$ProUrl?>">
                          <button type="submit" name="BtnAdd" class="btn-secondary BtnSubmit orderButton" style="margin: 0px">
                              <i class="fa fa-plus"></i>  Add to Cart
                          </button>
                      </div>
                    </div>
                    <div id="BtnOutStock" class="col-12 product-info-button<?=($ProStock > 0 ? ' hide' : '')?>">
                        <button type="button" class="btn-out-of-stock orderButton"><i class="fa fa-times"></i>  Out of Stock</button>
                    </div>
                  </div>
                </form>

              <!-- ACCORDION -->
                <div class="accor-box">
                  <div class="accordion setacc">
                    <div class="tab">
                      <input type="checkbox" name="accordion-1" id="cb1" checked>
                      <label for="cb1" class="tab_label">Details</label>
                      <div class="tab_content">
                        <?php
                          echo (!empty($ProDesc) ? "<p><strong>Description</strong><br>".$ProDesc."</p>" : "");
                          echo (!empty($Ingre) ? "<p><strong>Ingredients</strong><br>".$Ingre."</p>" : "");
                          echo (!empty($store) ? "<p><strong>Storage Instructions</strong><br>".$store."</p>" : "");
                          echo (!empty($life) ? "<p><strong>Shelf Life</strong><br>".$life."</p>" : "");
                        ?>
                      </div>
                    </div>

                    <!-- FOR MULTIPLE ACCORDION-->
                    <!-- <div class="tab">
                      <input type="checkbox" name="accordion-1" id="cb2">
                      <label for="cb2" class="tab_label">Open multiple</label>
                      <div class="tab_content">
                        <p>Using <code>&lt;input type="checkbox"&gt;</code> allows to have several tabs open at the same time.</p>
                      </div>
                    </div>-->
                  </div>
                </div>
              <!-- END ACCORDION -->

                <!---- For Rating   ---->
<?php
    # rating
    $RateInfo = $func->CalcReviewRate($ProID);
    $rate = $RateInfo["rate"];
    $TotalReview = $RateInfo["TotalReview"];
    $RateAvg = $RateInfo["RateAvg"];
    $RateAvgWidth = $RateInfo["RateAvgWidth"];
    # END rating

    # reviews
    echo "<div id=\"review-section\" class=\"col-12 mb-10 review-section px-0\">
            <h3>Ratings and Reviews</h3>";

        if($TotalReview > 0)
        {
          echo "<div class=\"row review-summary-box\">
                  <div class=\"col-md-6\">
                      <div class=\"rate-box\">
                          <div class=\"rate-star-box\">
                              <div class=\"rate-star-empty\"></div>
                              <div class=\"rate-star-avg\" style=\"width:".$RateAvgWidth."%\"></div>
                          </div>
                          <span class=\"rate-review\">".$RateAvg." / 5</span>
                      </div>
                      <p>Average rating from ".$TotalReview." review".($TotalReview <= 1 ? "" : "s")."</p>
                  </div>
                  <div class=\"col-md-6\">";

          for($star=5; $star>=1; $star--)
          {
            $review = empty($rate[$star]) ? 0 : $rate[$star];

            echo "<div class=\"rate-single-box".($review == 0 ? " inactive" : "")."\" data-rate=\"".$star."\" data-review=\"".$review."\">
                      <div class=\"rate-single-text-box\">
                          <div class=\"rate-single-star\">".$star." star".($star <= 1 ? "" : "s")."</div>
                          <div class=\"rate-single-review\">".$review." review".($review <= 1 ? "" : "s")."</div>
                      </div>
                      <div class=\"rate-single-bar\">
                          <div class=\"rate-single-bar-filled\" style=\"width:".round((($review/$TotalReview)*100),2)."%\"></div>
                      </div>
                  </div>";
          }

          echo "</div>
              </div>";

        }
        else
            echo "<p>Do not have any review for this product.</p>";

    echo "</div>";
    # END reviews
?>

              </aside>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main><!-- End #main -->
  <?php include("lib/footer.php"); ?>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

</body>
<script src="./assets/js/slide.js"></script>
<script>
  var domain = "https://localhost:80/FYP-Project/";
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