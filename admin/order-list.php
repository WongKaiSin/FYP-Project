<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <style>
       
    </style>
</head>

<body>
    <div class="dashboard-main-wrapper">
        <?php 
            include("lib/navbar.php");
            include("lib/sidebar.php");
        ?>
        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h2 class="pageheader-title">Order List</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">View Order List</li>
                                    </ol>
                                </nav>
                           </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="accrodion-regular">
                        <?php
                        mysqli_select_db($db_conn, "bagel");
                        $sql = "SELECT * FROM `order` o,`member` m,`order_product` op
                                where o.MemberID = m.MemberID and o.OrderID = op.OrderID";
                        $sql_address = "SELECT * FROM `member` m,`member_address` ma
                        where o.MemberID = m.MemberID "; 
                        $query = $db_conn->query($sql);
                        if ($query) {
                            while ($row = $query->fetch_assoc()) {
                                // Fetch order data here
                                ?>
                                <div id="accordion3">
                                    <div class="card">
                                        <div class="card-header" id="headingSeven">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="true" aria-controls="collapseSeven">
                                                    <span class="fas fa-angle-down mr-3"></span>Order #<?php echo $row["OrderID"]; ?>
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseSeven" class="collapse show" aria-labelledby="headingSeven" data-parent="#accordion3">
                                        <div class="card-header p-4">                                   
                                        <div class="float-right"> 
                                        Date: <?php echo $row["OrderDate"]; ?></div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-4">
                                                <div class="col-sm-6">
                                                    <h5 class="mb-3">Customer:</h5>                                            
                                                    <h3 class="text-dark mb-1"><?php echo $row["MemberName"]; ?></h3>
                                                
                                                    <div><?php echo $row["AddAddress"]; ?></div>
                                                    <div><?php echo $row["AddPostcode"]; ?><?php echo $row["AddCity"]; ?></div>
                                                    <div><?php echo $row["AddState"]; ?><?php echo $row["AddCountry"]; ?></div>
                                                    <div>Email: <?php echo $row["MemberEmail"]; ?></div>
                                                    <div>Phone: <?php echo $row["MemberPhone"]; ?></div>
                                                </div>
                                            </div>
                                                <div class="table-responsive-sm">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th class="center">#</th>
                                                            <th>Item</th>
                                                            <th>Remark</th>
                                                            <th class="right">Unit Cost</th>
                                                            <th class="center">Qty</th>
                                                            <th class="right">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="center">1</td>
                                                            <td class="left strong"><?php echo $row["ProName"]; ?></td>
                                                            <td class="left">Remark!!!!</td>
                                                            <td class="right">RM <?php echo $row["ProPrice"]; ?></td>
                                                            <td class="center"><?php echo $row["ProQty"]; ?></td>
                                                            <td class="right"><?php echo $row["ProTotal"]; ?></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo "Error fetching records: " . $db_conn->error;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
