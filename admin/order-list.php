<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");

// Default to current date if no order date is provided
$order_date = '';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['order_date'])) {
    $order_date = $_GET['order_date'];
}

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
                    <form method="GET" action="order-list.php" class="float-left">
                    <h5>Select Order Date 
                        <input type="date" class="form-control" id="order_date" name="order_date" value="<?php echo $order_date; ?>">
                    </h5>
                    <button type="submit" class="btn btn-primary btn-xs">View Orders</button>
                    <button type="button" class="btn btn-secondary btn-xs" onclick="resetDate()">Reset</button>
                    </form>
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
                        mysqli_select_db($db_conn, "bagel");
                        mysqli_select_db($db_conn, "bagel");
                        $sql = "SELECT o.*, ma.*, p.*
                        FROM `order` o
                        JOIN `member_address` ma ON o.MemberID=ma.MemberID 
                        JOIN `payment` p ON o.paymentID=p.paymentID
                        ORDER BY OrderDate ASC";
                        
                        if ($order_date) {
                            $sql = "SELECT o.*, ma.*, p.*
                            FROM `order` o
                            JOIN `member_address` ma ON o.MemberID=ma.MemberID 
                            JOIN `payment` p ON o.paymentID=p.paymentID
                            WHERE DATE(o.OrderDate)='$order_date'
                            ORDER BY OrderDate ASC";
                        }

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
