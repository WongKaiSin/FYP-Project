<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");


// Handle form submission for selecting order status
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['status'])) {
    $selected_status = $_GET['status'];

    // Modify your SQL query based on the selected status
    if ($selected_status != 'All') {
        $sql = "SELECT o.*, ma.*, p.*
                FROM `order` o
                JOIN `member_address` ma ON o.MemberID=ma.MemberID 
                JOIN `payment` p ON o.paymentID=p.paymentID
                WHERE o.OrderStatus = '$selected_status'
                ORDER BY o.OrderDate ASC";
    } else {
        // Default query to fetch all orders
        $sql = "SELECT o.*, ma.*, p.*
                FROM `order` o
                JOIN `member_address` ma ON o.MemberID=ma.MemberID 
                JOIN `payment` p ON o.paymentID=p.paymentID
                ORDER BY o.OrderDate ASC";
    }
} else {
    // Default SQL query
    $sql = "SELECT o.*, ma.*, p.*
            FROM `order` o
            JOIN `member_address` ma ON o.MemberID=ma.MemberID 
            JOIN `payment` p ON o.paymentID=p.paymentID
            ORDER BY o.OrderDate ASC";
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <style>
     #statusDropdown {
        padding: 5px; 
        margin:5px;
    }
    button {
         
        margin-left:5px;
    }
</style>
</head>
<script>
    function resetDate() {        
        
        window.location.href = "order-list.php";
    }
</script>
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
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                                <form method="GET" action="">
                                    <h5>Select Order Status
                                        <div>
                                        <select id="statusDropdown" name="status">
                                            <option value="All"<?php if(isset($_GET['status']) && $_GET['status'] == 'All') echo ' selected'; ?>>All</option>
                                            <option value="Preparing"<?php if(isset($_GET['status']) && $_GET['status'] == 'Preparing') echo ' selected'; ?>>Preparing</option>
                                            <option value="Shipping"<?php if(isset($_GET['status']) && $_GET['status'] == 'Shipping') echo ' selected'; ?>>Shipping</option>
                                            <option value="Complete"<?php if(isset($_GET['status']) && $_GET['status'] == 'Complete') echo ' selected'; ?>>Complete</option>
                                            <option value="Cancel"<?php if(isset($_GET['status']) && $_GET['status'] == 'Cancel') echo ' selected'; ?>>Cancel</option>
                                        </select>
                                        </div>
                                    <button type="submit" class="btn btn-outline-primary btn-xs">View Orders</button>
                                </form></h5>
                            </div>
                        </div>

                        <div class="card-body">
                        <p>
                        <i class="m-r-10 fas fa-stopwatch"> Preparing</i>
                        <i class="m-r-10 fas fa-shipping-fast"> Shipping</i>
                        <i class="m-r-10 fas fa-dolly"> Complete</i>
                        <i class="m-r-10 fas fa-times"> Cancel</i></p>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">OrderNumber</th>
                                        <th scope="col">User Name</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Payment Type</th>
                                        <th scope="col">Total Payment(RM)</th>
                                        <th scope="col">Order Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                        <?php

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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
