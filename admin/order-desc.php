<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");

// Retrieve the ID from the URL
$OrderID = isset($_GET['OrderID']) ? $_GET['OrderID'] : null;

$query = "SELECT * FROM `order` o, member_address ma WHERE o.OrderID = '$OrderID' AND o.MemberID=ma.MemberID";

$result = $db_conn->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $datetime = $row['OrderDate'];
    $date = date("Y-m-d", strtotime($datetime));

} 
else {
        // Handle case where order is not found
        echo "Order not found!";
        exit;
    }

$productNumber = 1;


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    // Get the new status and OrderID from the POST data
    $newStatus = $_POST["new_status"];
    $OrderID = $_POST["OrderID"];

    // Perform update query
    $sql = "UPDATE `order` SET OrderStatus = '$newStatus' WHERE OrderID = '$OrderID'";
    $result = $db_conn->query($sql);

    if ($result) {
        // Update successful
        echo '<script type="text/javascript">
                    window.location.href = "order-desc.php?OrderID='.$OrderID.'";
            </script>';
    } else {
        // Update failed
        echo "Error updating order information: " . $db_conn->error;
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>

</head>
<style>
     #statusDropdown {
        padding: 5px; 
        margin:5px;
    }
</style>
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
                            <h2 class="pageheader-title">Order Details</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="order-list.php" class="breadcrumb-link">View Order List</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Order Details</li>
                                    </ol>
                                </nav>
                           </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header p-4">
                        <h3 class="mb-0">Order #<?php echo $row['OrderNo']; ?></h3>
                        Date: <?php echo $date; ?>
                                   
                        <?php
                            $statusOptions = [
                                "Complete" => '<option value="Complete">Complete &#10003;</option>',
                                "Shipping" => '<option value="Shipping">Shipping &#128722;</option>',
                                "Cancel" => '<option value="Cancel">Cancel &#10006;</option>',
                                "Preparing" => '<option value="Preparing">Preparing &#9203;</option>'
                            ];
                        ?>

                        <div class="float-right">
                            <form method="POST" action="">
                                <div>
                                    <select id="statusDropdown" name="new_status">
                                        <?php 
                                        // Output the appropriate status options based on the current status
                                        if ($row['OrderStatus'] == "Complete") { 
                                            echo $statusOptions["Complete"];
                                        } elseif ($row['OrderStatus'] == "Shipping") { 
                                            echo $statusOptions["Shipping"];
                                            echo $statusOptions["Complete"];
                                        } elseif ($row['OrderStatus'] == "Cancel") { 
                                            echo $statusOptions["Cancel"];
                                        } elseif ($row['OrderStatus'] == "Preparing") { 
                                            echo $statusOptions["Preparing"];
                                            echo $statusOptions["Shipping"];
                                            echo $statusOptions["Cancel"];
                                        } 
                                        ?>
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" name="update_status" class="btn btn-light btn-xs">Update Status</button>
                                </div>
                                <!-- Hidden input field to pass OrderID -->
                                <input type="hidden" name="OrderID" value="<?php echo $OrderID; ?>">
                            </form>
                        </div>
                    </div>
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-sm-6">
                                            <h5 class="mb-3">Customer:</h5>                                            
                                            <h3 class="text-dark mb-1"><?php echo $row['AddName']; ?></h3>
                                         
                                            <div><?php echo $row['AddAddress']; ?></div>
                                            <div><?php echo $row['AddPostcode']; ?>, <?php echo $row['AddCity']; ?></div>
                                            <div>Phone: <?php echo $row['AddPhone']; ?></div>
                                        </div>
                                    </div>
                                    <div class="table-responsive-sm">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="center">#</th>
                                                    <th>Item</th>
                                                    <th class="right">Unit Cost</th>
                                                    <th class="center">Qty</th>
                                                    <th class="right">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $query = "SELECT * FROM `order_product` WHERE OrderID = '$OrderID'";
                                            $result = $db_conn->query($query);
                                            
                                            if ($result && $result->num_rows > 0) {
                                                while ($product_row = $result->fetch_assoc()) {
                                                    ?>
                                                    <tr>
                                                        <td class="center"><?php echo $productNumber++; ?></td>
                                                        <td class="left strong"><?php echo $product_row['ProName']; ?></td>
                                                        <td class="right">RM <?php echo $product_row['ProPrice']; ?></td>
                                                        <td class="center"><?php echo $product_row['ProQty']; ?></td>
                                                        <td class="right">RM <?php echo $product_row['ProTotal']; ?></td>
                                                    </tr>
                                                    <?php 
                                                }
                                            } else {
                                                // Handle case where no order products are found
                                                echo "No order products found for this order!";
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td style="border: none;"></td>
                                                    <td style="border: none;"></td>
                                                    <td style="border: none;"></td>
                                                    <td style="border-top: 1px dashed #d3d3d3;" class="right"><strong class="text-dark">Subtotal</strong></td>
                                                    <td style="border-top: 1px dashed #d3d3d3;" class="right">RM <?php echo $row['OrderSubtotal']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none;"></td>
                                                    <td style="border: none;"></td>
                                                    <td style="border: none;"></td>
                                                    <td style="border-top: 1px dashed #d3d3d3;" class="right"><strong class="text-dark">Shipping Fees</strong></td>
                                                    <td style="border-top: 1px dashed #d3d3d3;" class="right">RM <?php echo $row['OrderShipping']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none;"></td>
                                                    <td style="border: none;"></td>
                                                    <td style="border: none;"></td>
                                                    <td style="border-top: 1px dashed #d3d3d3;" class="right"><strong class="text-dark">Total Payment</strong></td>
                                                    <td style="border-top: 1px dashed #d3d3d3;" class="right"><strong class="text-dark">RM <?php echo $row['OrderTotal']; ?></strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

