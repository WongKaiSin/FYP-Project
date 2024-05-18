<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");

$OrderID = isset($_GET['OrderID']) ? $_GET['OrderID'] : null;
$query = "SELECT * FROM `order_product` op, `review_comment` rc WHERE op.OrderID = '$OrderID' AND  op.OrderID=rc.OrderID";

$result = $db_conn->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    

} 
else {
        
        echo '<script type="text/javascript">
        alert("No rating and comment for this order");
        window.location.href = "order-desc.php?OrderID=' . $OrderID . '";
        </script>';

exit();
    }
    $productNumber = 1;

// Function to generate star icons based on rating value
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the form is submitted via POST method
    if (isset($_POST['approve'])) {
        // Perform update query
        $Csql = "UPDATE `review_comment` SET Approval = '1' WHERE OrderID = '$OrderID'";
        $Rsql = "UPDATE `review_rate` SET Approval = '1' WHERE OrderID = '$OrderID'";
        $resultC = $db_conn->query($Csql);
        $resultR = $db_conn->query($Rsql);
        
        // Check if updates were successful
        if ($resultC && $resultR) {
            // Updates successful
            echo '<script type="text/javascript">
                        alert("Rating and Comment approve successfully.");
                        window.location.href = "order-rating.php?OrderID=' . $OrderID . '";
                  </script>';
        } else {
            // Update failed
            echo "Error updating booking information: " . $db_conn->error;
        }
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <title>Order Rating</title>
</head>
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
                            <h2 class="pageheader-title">Order Rating</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="order-list.php" class="breadcrumb-link">View Order List</a></li>
                                        <li class="breadcrumb-item"><a href="order-desc.php?OrderID=<?php echo $row['OrderID']; ?>" class="breadcrumb-link">Order Details</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Order Rating</li>
                                    </ol>
                                </nav>
                           </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                            <?php if ($row['Approval'] == 0) { ?>
                            <div class="card-header">
                                <form method="post">
                                    <div class="float-right">
                                        <button type="submit" name="approve" class="btn btn-light">Approve</button>
                                    </div>
                                </form>
                            </div>
                            <?php } else { ?>
                                <div class="card-header">
                                    <div class="float-right">
                                        <p>Approved</p>
                                    </div>
                                </div>
                            <?php } ?>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Product</th>
                                                <th scope="col">Rate</th>
                                            </tr>
                                        </thead>
                                         <tbody>
                                <?php
                                $query = "SELECT op.ProID, op.ProName, rr.RevRate FROM `order_product` op LEFT JOIN `review_rate` rr ON op.OrderID = rr.OrderID AND op.ProID = rr.ProID WHERE op.OrderID = '$OrderID'";
                                $result = $db_conn->query($query);

                                if ($result && $result->num_rows > 0) {
                                    while ($product_row = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <th scope="row"><?php echo $productNumber++; ?></th>
                                            <td><?php echo $product_row['ProName']; ?></td>
                                            <td><?php echo ($product_row['RevRate'] !== null) ? displayStars($product_row['RevRate']) : 'No rating'; ?></td>
                                        </tr>
                                        <?php 
                                    }
                                } else {
                                    // Handle case where no order products are found
                                    echo '<tr><td colspan="3">No products found for this order</td></tr>';
                                }
                                ?>
                                <tr>
                                    <th style="background-color:whitesmoke; border: 1px dotted;" colspan="3"><strong>Order Comment</strong></th>
                                </tr>
                                <tr>
                                    <td style="border:none;" colspan="3"><?php echo $row['Comment']; ?></td>
                                </tr>
                            </tbody>
                                    </table>

                                    
                                </div>
                            </div>
                        </div>
    </body>
</html>