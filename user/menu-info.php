<?php
require_once("lib/db.php");
$SiteUrl = "http://localhost:80/FYP-Project";

if(isset($_GET["ProUrl"]))
  $ProUrl = $_GET["ProUrl"];

$pro_sql = "SELECT * FROM product WHERE `ProUrl` = '".$ProUrl."'";
$pro_query = $db_conn ->query($pro_sql);

if($pro_query->num_rows > 0)
{
  $row = $pro_query->fetch_assoc();

  $ProID = $row["ProID"];
  $ProName = $row["ProName"];
  $ProPrice = $row["ProPrice"];
  $ProDesc = $row["ProDesc"];
  $Ingre = $row["Ingredient"];
  $store = $row["Storage"];
  $life = $row["ShelfLife"];

  // Category
  $cat_sql = "SELECT CatName FROM product_cat WHERE ProID = ".$ProID."";
  $cat_query = $db_conn ->query($cat_sql);

  $cat_row = $cat_query->fetch_assoc();
  $CatName = $cat_row["CatName"];
  // End Category

  // For Image
  $img_sql = "SELECT `ImageName`, `ImageExt` FROM product_image WHERE `ProID` = ".$ProID."";
  $img_query = $db_conn ->query($img_sql);

  $img_row = $img_query->fetch_assoc();
  if($img_row === NULL)
    $img_num = 0;
  else 
      $img_num = count($img_row);

  if($img_num > 0)
  {
    if($img_num > 1)
    {
      $img_lay ="<div class='cont'>";
      $i=0;
      while($img_row = $img_query->fetch_assoc())
      {
        $ImageName = $img_row["ImageName"];
        $ImageExt = $img_row["ImageExt"];

        $img_lay .="<div class='mySlides'>
                      <div class='numbertext'>".($i+1)." / $img_num</div>
                        <a href='$SiteUrl/upload/product/$ImageName.$ImageExt'>
                          <img src='$SiteUrl/upload/product/$ImageName.$ImageExt' style='width:100%'>
                        </a>
                    </div>";
        $i++;
      }
      $img_lay .="  <!-- Next and previous buttons -->
                    <a class='prev' onclick='plusSlides(-1)'>&#10094;</a>
                    <a class='next' onclick='plusSlides(1)'>&#10095;</a>
                  
                    <!-- Image text -->
                    <div class='caption-container'>
                      <p id='caption'></p>
                    </div>
                  
                  <!-- Thumbnail images -->
                  <div class='row'>";

      // Reset the query to fetch images again
      $img_query->data_seek(0);
      $i=1;
      
      while($img_row = $img_query->fetch_assoc())
      {
        $ImageName = $img_row["ImageName"];
        $ImageExt = $img_row["ImageExt"];

        $img_lay .="<div class='column'>
                      <img class='demo cursor' src='$SiteUrl/upload/product/$ImageName.$ImageExt'  style='width:100%' onclick='currentSlide($i)' alt='The Woods'>
                    </div>";
        $i++;
      }
      $img_lay .="</div>";
    }
    else
    {
      while($img_row = $img_query->fetch_assoc())
      {
        $ImageName = $img_row["ImageName"];
        $ImageExt = $img_row["ImageExt"];

        $img_lay .= " <a href='$SiteUrl/upload/product/$ImageName.$ImageExt'>
                        <img src='$SiteUrl/upload/product/$ImageName.$ImageExt'>
                      </a>";
      }
    }
  }
  else
  {
    $img_lay .="<img src='$SiteUrl/user/assets/img/no-image.png'>";
  }
  $img_lay .="</div>";
  // End Image
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("lib/head.php"); ?>
  <title><?=$ProName // food name?> - LBM</title>
  <style>
    /* Position the image cont (needed to position the left and right arrows) */
    .cont
    {
      position: relative;
    }
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
      <div class="row" style="margin-bottom:10px">
			  <div class="col-md-6">
          <?php echo $img_lay; ?>
          <div class="product-info-desc d-sm-block d-none">
            <div class="accor-box">
                <h4 class="toggle-arrow enable">Descriptions</h4>
                <div class="accor-content toggle-result">
                    <?php
                      echo (!empty($ProDesc) ? $ProDesc : "");
                      echo (!empty($Ingre) ? "<p><strong>Ingredients</strong><br>".$Ingre."</p>" : "");
                      echo (!empty($store) ? "<p><strong>Storage Instructions</strong><br>".$store."</p>" : "");
                      echo (!empty($life) ? "<p><strong>Shelf Life</strong><br>".$life."</p>" : "");
                    ?>
                </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
				  <div class="product-info-side">
					  <aside>
              <h3><?=$ProName?></h3>
            </aside>
          </div>
        </div>
      </div>
        <!-- <div class="row mt-30">
          <div class="col product-gallery col-lg-6">
            <div class="row">
              <div class="col col-md-12 col-sm-12 product-images">
                <img src="http://localhost:80/FYP-Project/user/upload/product/<?php // echo $ImgName.".".$ImgExt; ?>" >
              </div>
            </div>
            <p>
              You can duplicate this sample page and create any number of inner pages you like!
            </p>
          </div>
        </div> -->
      </div>
    </section>

  </main><!-- End #main -->
  <?php include("lib/footer.php"); ?>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

</body>
</html>