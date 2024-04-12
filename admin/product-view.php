<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");

?>
<!doctype html>
<html lang="en">

<head>
  
    <title>View Product List</title>
</head>

<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
        <?php 
            include("lib/navbar.php");
            include("lib/sidebar.php");
        ?>


        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                <!-- ============================================================== -->
                <!-- pageheader -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h2 class="pageheader-title">View Product List</h2>

                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="product-view.php" class="breadcrumb-link">View Product List</a></li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end pageheader -->
                <!-- ============================================================== -->
                
                <h3>Drinks</h3>
                <!-- grid column -->
                <div class="row">
                    <?php
                    mysqli_select_db($db_conn,"bagel");
                    $result = mysqli_query($db_conn, "SELECT * FROM product");	
                    $count = mysqli_num_rows($result);
                    $i = 0;
                    while($row = mysqli_fetch_assoc($result))
                    {
                        if($i % 3 == 0) {
                            echo '</div><div class="row">';
                        }
                    ?>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                        <!-- .card -->
                        <div class="card card-figure">
                            <!-- .card-figure -->
                            <figure class="figure">
                                <!-- .figure-img -->
                                <div class="figure-img">
                                    <img class="img-fluid" src="assets/images/card-img.jpg" alt="Card image cap">
                                    <div class="figure-action">
                                        <a href="#" class="btn btn-block btn-sm btn-primary">Description</a>
                                    </div>
                                </div>
                                <!-- /.figure-img -->
                                <!-- .figure-caption -->
                                <figcaption class="figure-caption">
                                    <p class="text-muted mb-0"><?php echo $row["ProName"]; ?></p>
                                </figcaption>
                                <!-- /.figure-caption -->
                            </figure>
                            <!-- /.card-figure -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <?php
                        $i++;
                    }
                    ?>
                </div>
                <!-- /grid column -->
            </div>
        </div>
    </div>
</body>

</html>
