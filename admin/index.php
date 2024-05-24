<?php include("lib/validation.php"); 
include("lib/db.php");
$sqlSalesByCategory = "SELECT pc.CatName, SUM(op.ProQty) AS category_sales
                        FROM order_product op
                        JOIN product_cat pc ON op.ProID = pc.ProID
                        JOIN `order` o ON op.OrderID = o.OrderID
                        WHERE o.OrderStatus = 'Complete'
                        GROUP BY pc.CatName
                        ORDER BY category_sales DESC";

$querySalesByCategory = $db_conn->query($sqlSalesByCategory);
$categoryNames = [];
$categorySales = [];

if ($querySalesByCategory) {
    while ($row = $querySalesByCategory->fetch_assoc()) {
        $categoryNames[] = $row['CatName'];
        $categorySales[] = (float)$row['category_sales'];
    }
} else {
    echo "Error fetching sales by category: " . $db_conn->error;
}
function displayStars($rating) {
    $output = '';
    $fullStars = intval($rating); // Full stars
    $halfStar = $rating - $fullStars; // Half star

    // Full stars
    for ($i = 0; $i < $fullStars; $i++) {
        $output .= '<span class="fa fa-star checked"></span>';
    }

    // Half star
    if ($halfStar >= 0.5) {
        $output .= '<span class="fa fa-star-half-o checked"></span>';
    }

    // Empty stars
    for ($i = 0; $i < (5 - ceil($rating)); $i++) {
        $output .= '<span class="fa fa-star"></span>';
    }

    return $output;
}
?>
<!doctype html>
<html lang="en">

<head>
    <?php include("lib/head.php"); ?>
    <title>London Bagel Museum</title>
</head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<style>

.star-rating {
    unicode-bidi: bidi-override;
    color: #c5c5c5;
    font-size: 16px;
    height: 1em;
    width: 5em;
    margin: 0 auto;
    position: relative;
    padding: 0;
}

.star-rating span {
    display: inline-block;
    position: absolute;
    overflow: hidden;
    top: 0;
    left: 0;
    width: 0;
}

.star-rating span.star-filled {
    color: #ffcc00;
    width: auto;
}

.checked {
    color: orange;
}
</style>
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
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content ">
                <div class="row">
                    <!-- metric -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">
                            <?php
                            $sql = "SELECT SUM((p.ProPrice - p.ProCost) * op.ProQty) AS profit
                                    FROM `order_product` op
                                    JOIN `product` p ON op.ProID = p.ProID
                                    JOIN `order` o ON op.OrderID = o.OrderID
                                    WHERE o.OrderStatus = 'Complete' AND DATE(`OrderDate`) = CURDATE()";

                            $query = $db_conn->query($sql);
                            if ($query && $row = $query->fetch_assoc()) {
                                $profit = $row["profit"];
                            } else {
                                $profit = 0;
                            }
                            ?>
                                <h5 class="text-muted">Today's Profit</h5>
                                <div class="metric-value d-inline-block">
                                    <h2 class="mb-1 text-primary">RM <?php echo number_format($profit, 2); ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /. metric -->
                    <!-- metric -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">
                            <?php
                                $sql = "SELECT p.ProName, SUM(op.ProQty) AS TotalSales
                                        FROM order_product op
                                        JOIN product p ON op.ProID = p.ProID
                                        JOIN `order` o ON op.OrderID = o.OrderID
                                        WHERE o.OrderStatus = 'Complete'
                                        GROUP BY p.ProID
                                        ORDER BY SUM(op.ProQty) DESC
                                        LIMIT 1";

                                $query = $db_conn->query($sql);
                                if ($query) {
                                    if ($row = $query->fetch_assoc()) {
                                        $top_pro_name = $row["ProName"];
                                    } else {
                                        // No results found
                                        $top_pro_name = "N/A"; 
                                    }
                                } else {
                                    // Handle query error
                                    $top_pro_name = "N/A"; 
                                }
                            ?>
                                <h5 class="text-muted">Best Selling Product</h5>
                                <div class="metric-value d-inline-block">
                                    <h2 class="mb-1 text-primary"><?php echo $top_pro_name; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /. metric -->
                    <!-- metric -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">
                                <?php
                                   
                                $sql = "SELECT COUNT(BookID) AS total_book
                                FROM `booking`
                                WHERE DATE(`BookAddDate`) = CURDATE()";

                                            $query = $db_conn->query($sql);
                                            if ($query) {
                                                while ($row = $query->fetch_assoc()) {
                                                    $total_book = $row["total_book"];
                                                }
                                            }
                                            else {
                                                // Handle query error
                                                $total_book = 0; 
                                            }
                                            ?>
                                <h5 class="text-muted">Today's Booking</h5>
                                <div class="metric-value d-inline-block">
                                    <h2 class="mb-1 text-primary"><?php echo $total_book; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /. metric -->
                    <!-- metric -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">
                                <?php
                                   
                                $sql = "SELECT COUNT(OrderID) AS total_order
                                FROM `order`
                                WHERE DATE(`OrderDate`) = CURDATE()";

                                            $query = $db_conn->query($sql);
                                            if ($query) {
                                                while ($row = $query->fetch_assoc()) {
                                                    $total_order = $row["total_order"];
                                                }
                                            }
                                            else {
                                                // Handle query error
                                                $total_order = 0; 
                                            }
                                            ?>
                                <h5 class="text-muted">Today's order</h5>
                                <div class="metric-value d-inline-block">
                                    <h2 class="mb-1 text-primary"><?php echo $total_order; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /. metric -->
                </div>         
                <div class="row">
                    <div class="col-xl-8 col-lg-12 col-md-8 col-sm-12 col-12">
                        <div class="card">
                            <h5 class="card-header">Sales by Category</h5>
                            <div class="card-body">
                                <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end reveune  -->
                    <!-- ============================================================== -->
                    <div class="col-xl-4 col-lg-12 col-md-6 col-sm-12 col-12">
                                <!-- ============================================================== -->
                                <!-- top perfomimg  -->
                                <!-- ============================================================== -->
                                <div class="card">
                                    <h5 class="card-header">Top Rated Products</h5>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table no-wrap p-table">
                                                <thead class="bg-light">
                                                    <tr class="border-0">
                                                        <th class="border-0">#</th>
                                                        <th class="border-0">Product</th>
                                                        <th class="border-0">Average rating</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $query = "SELECT p.ProName, AVG(rr.RevRate) as avgrate 
                                                    FROM product p 
                                                    LEFT JOIN review_rate rr ON p.ProID = rr.ProID 
                                                    GROUP BY p.ProID 
                                                    ORDER BY avgrate DESC LIMIT 7";
                                                    $result = $db_conn->query($query);
                                                    $productNumber=1;
                                                    if ($result && $result->num_rows > 0) {
                                                        while ($product_row = $result->fetch_assoc()) {
                                                            ?>
                                                            <tr>
                                                            <th scope="row"><?php echo $productNumber++; ?></th>
                                                                <td><?php echo $product_row['ProName']; ?></td>
                                                                <td><?php echo ($product_row['avgrate'] !== null) ? displayStars($product_row['avgrate']) : 'No rating'; ?></td>
                                                            </tr>
                                                            <?php 
                                                        }
                                                    } else {
                                                        // Handle case where no order products are found
                                                        echo '<tr><td colspan="3">No products found for this order</td></tr>';
                                                    }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- ============================================================== -->
                                <!-- end top perfomimg  -->
                                <!-- ============================================================== -->
                            </div>
                    </div>
                <div class="row">
                    <!-- ============================================================== -->
                    <!-- ORDER  -->
                    <!-- ============================================================== -->
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <h5 class="card-header">Order list</h5>
                            
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                    <p style="padding:10px;"><i class="m-r-10 fas fa-stopwatch"> Preparing</i>
                                    <i class="m-r-10 fas fa-shipping-fast"> Shipping</i>
                                    <i class="m-r-10 fas fa-dolly"> Complete</i>
                                    <i class="m-r-10 fas fa-times"> Cancel</i></p>
                                        <thead class="bg-light">
                                            <tr class="border-0">
                                                <th class="border-0">#</th>
                                                <th class="border-0">OrderNumber</th>
                                                <th class="border-0">User Name</th>
                                                <th class="border-0">Phone</th>
                                                <th class="border-0">Payment Type</th>
                                                <th class="border-0">Total Payment(RM)</th>
                                                <th class="border-0">Order Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $sql = "SELECT o.*, ma.*, p.*
                                            FROM `order` o
                                            JOIN `member_address` ma ON o.MemberID=ma.MemberID 
                                            JOIN `payment` p ON o.paymentID=p.paymentID
                                            ORDER BY OrderDate ASC
                                            LIMIT 5";

                                            $query = $db_conn->query($sql);
                                            if ($query) {
                                                while ($row = $query->fetch_assoc()) {
                                                    if($row['OrderStatus']=="Complete"){
                                                        $status='<i class="m-r-10 fas fa-dolly">';
                                                    }
                                                    else if($row['OrderStatus']=="Preparing"){
                                                        $status='<i class="m-r-10 fas fa-stopwatch">';
                                                    }
                                                    else if($row['OrderStatus']=="Shipping"){
                                                        $status='<i class="m-r-10 fas fa-shipping-fast">';
                                                    }
                                                    else if($row['OrderStatus']=="Cancel"){
                                                        $status='<i class="m-r-10 fas fa-times">';
                                                    }
                                                    else{
                                                        $status='';
                                                    }

                                                    ?>
                                                    <tr onclick="window.location='order-desc.php?OrderID=<?php echo $row['OrderID']; ?>';" style="cursor: pointer;">
                                                        <td scope="row"><?php echo $status; ?></td>
                                                        <td><?php echo $row['OrderNo']; ?></td>
                                                        <td><?php echo $row['AddName']; ?></td>
                                                        <td><?php echo $row['AddPhone']; ?></td>
                                                        <td><?php echo $row['PaymentName']; ?></td>
                                                        <td><?php echo $row['OrderTotal']; ?></td>
                                                        <td><?php echo $row['OrderDate']; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo "Error fetching records: " . $db_conn->error;
                                            }
                                            ?>
                                            <tfoot>
                                                <td colspan="7"><a href="order-list.php" class="btn btn-outline-light float-right">View More</a></td>
                                            </tfoot>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end order  -->
                    <!-- ============================================================== -->
                    </div>
                    <div class="row">
                    <!-- ============================================================== -->
                    <!-- BOOKING  -->
                    <!-- ============================================================== -->
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <h5 class="card-header">Booking list</h5>
                            
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <p style="padding:10px;">
                                            <span class="mr-2"><span class="badge-dot badge-success"></span>Approved</span>
                                            <span class="mr-2"> <span class="badge-dot badge-warning"></span>Waiting Approval</span>
                                            <span class="mr-2"> <span class="badge-dot badge-dark"></span>Reject</span>
                                        </p>
                                        <thead class="bg-light">
                                            <tr class="border-0">
                                                <th class="border-0">#</th>
                                                <th class="border-0">Name</th>
                                                <th class="border-0">Phone</th>
                                                <th class="border-0">Email</th>
                                                <th class="border-0">Time</th>
                                                <th class="border-0">People(s)</th>
                                            </tr>
                                        </thead>
                                        <?php
                                            $sql = "SELECT * FROM booking ORDER BY `Date`, `Time`, `BookAddDate` DESC LIMIT 5;";
                                            $query = $db_conn->query($sql);
                                            if ($query) {
                                                $current_date = '';
                                                while ($row = $query->fetch_assoc()) {
                                                    if ($current_date != $row['Date']) {
                                                        echo '<tr style="background-color:whitesmoke ;"><td colspan="7"><strong>' . $row['Date'] . '</strong></td></tr>';
                                                        $current_date = $row['Date'];
                                                    }
                                                    if ($row['Approval'] == 0) {
                                                        $status = 'class="badge-dot badge-warning"';
                                                    } else if ($row['Approval'] == 1) {
                                                        $status = 'class="badge-dot badge-success"';
                                                    } else if ($row['Approval'] == 2) {
                                                        $status = 'class="badge-dot badge-dark"';
                                                    }
                                                ?>
                                                    <tr>
                                                        <th scope="row"><span <?php echo $status; ?>></span></th>
                                                        <td><?php echo $row['Name']; ?></td>
                                                        <td><?php echo $row['Phone']; ?></td>
                                                        <td><?php echo $row['Email']; ?></td>
                                                        <td><?php echo $row['Time']; ?></td>
                                                        <td><?php echo $row['People']; ?></td>
                                                    </tr>
                                            <?php
                                                }
                                            } else {
                                                echo "Error fetching records: " . $db_conn->error;
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                                <td colspan="7"><a href="booking.php" class="btn btn-outline-light float-right">View More</a></td>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end top selling products  -->
                    <!-- ============================================================== -->
                    </div>
        </div>
        <!-- ============================================================== -->
        <!-- end wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
</body>
</html>
<script>
    
    var categoryNames = <?php echo json_encode($categoryNames); ?>;
    var categorySales = <?php echo json_encode($categorySales); ?>;
    var barColors = [
        "#fd7f6f", "#7eb0d5", "#b2e061", "#bd7ebe", "#ffb55a", "#ffee65", "#beb9db", "#fdcce5", "#8bd3c7"
    ];

        const doughnutChartConfig = {
            type: "doughnut",
            data: {
                labels: categoryNames,
                datasets: [{
                    backgroundColor: barColors,
                    data: categorySales
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "Sales by Category"
                }
            }
        };
        new Chart(document.getElementById("myChart"), doughnutChartConfig);
</script>