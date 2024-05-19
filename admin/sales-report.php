<?php
session_start();

include("lib/head.php"); 
include("lib/db.php");

mysqli_select_db($db_conn, "bagel");

// Fetch data from the database
$sql = "SELECT p.ProName, SUM(op.ProQty) as total_quantity
        FROM order_product op
        JOIN product p ON op.ProID = p.ProID
        JOIN `order` o ON op.OrderID = o.OrderID
        WHERE o.OrderStatus = 'Complete'
        GROUP BY p.ProName";

$query = $db_conn->query($sql);
$products = [];
$qty = [];

if ($query) {
    while ($row = $query->fetch_assoc()) {
        $products[] = $row['ProName'];
        $qty[] = $row['total_quantity'];
    }
} else {
    echo "Error fetching records: " . $db_conn->error;
}


$currentMonth = date('m');
$currentYear = date('Y');
$numDaysInMonth = date('t'); // Get the number of days in the current month

$sql = "SELECT DATE(o.OrderDate) AS order_date, SUM((p.ProPrice - p.ProCost) * op.ProQty) AS daily_profit 
        FROM order_product op
        JOIN product p ON op.ProID = p.ProID
        JOIN `order` o ON op.OrderID = o.OrderID
        WHERE MONTH(o.OrderDate) = $currentMonth AND YEAR(o.OrderDate) = $currentYear
        AND o.OrderStatus = 'Complete'
        GROUP BY DATE(o.OrderDate)";

$query = $db_conn->query($sql);
$dates = [];
$profits = [];

if ($query) {
    while ($row = $query->fetch_assoc()) {
        $dates[] = $row['order_date']; // Collect all order dates
        $profits[] = (int)$row['daily_profit'];
    }
} else {
    echo "Error fetching records: " . $db_conn->error;
}
$num=1;
?>
<!doctype html>
<html lang="en">

<head>
    <title>Sales Report</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>
<style>
    .button-container {
        text-align: right;
    }
    .button-container button {
        margin-top: 10px; 
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
                            <h2 class="pageheader-title">Sales Report</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Sales Report</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Bar Chart -->
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">
                                <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- Line Chart -->
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">
                                <canvas id="myChart2" style="width:100%;max-width:600px"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Sales Report Sorted by Highest Profit  </h5>
                        </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered second" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Product</th>
                                                <th>Category</th>
                                                <th>Sales Quantity</th>
                                                <th>Total Sales(RM)</th>
                                                <th>Total Cost(RM)</th>
                                                <th>Total Profits(RM)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            

                                            $sql = "SELECT  p.ProName, pc.catName, SUM((p.ProPrice - p.ProCost) * op.ProQty) AS profit,
                                                    SUM(op.ProQty) AS Sales, SUM(p.ProCost* op.ProQty) AS TotalCost, SUM(p.ProPrice* op.ProQty) AS TotalIncome
                                                    FROM order_product op
                                                    JOIN product p ON op.ProID = p.ProID
                                                    JOIN product_cat pc ON p.ProID = pc.ProID  
                                                    JOIN `order` o ON op.OrderID = o.OrderID
                                                    WHERE o.OrderStatus = 'Complete'
                                                    GROUP BY p.ProName ORDER BY profit DESC" ;

                                                    
                                            $query = $db_conn->query($sql);
                                            if ($query) {
                                                while ($row = $query->fetch_assoc()) {
                                                    

                                            ?>
                                                    <tr>
                                                        <td><?php echo $num++; ?></td>
                                                        <td><?php echo $row["ProName"]; ?></td>
                                                        <td><?php echo $row["catName"]; ?></td>
                                                        <td><?php echo $row["Sales"]; ?></td>
                                                        <td><?php echo $row["TotalIncome"]; ?></td>
                                                        <td><?php echo $row["TotalCost"]; ?></td>
                                                        <td><?php echo $row["profit"]; ?></td>
                                                    </tr>
                                            <?php
                                                }
                                            } else {
                                                echo "Error fetching records: " . $db_conn->error;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <div class="button-container">
                                        <button class="btn btn-outline-info" onclick="window.print()">Print Report</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    const barChartLabels = <?php echo json_encode($products); ?>;
    const barChartData = <?php echo json_encode($qty); ?>;
    const singleColor = "#cccbfd";

    // Bar Chart Configuration
    const barChartConfig = {
        type: "bar",
        data: {
            labels: barChartLabels,
            datasets: [{
                backgroundColor: singleColor,
                data: barChartData
            }]
        },
        options: {
            legend: { display: false },
            title: {
                display: true,
                text: "Product Sales"
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem) {
                        return Number(tooltipItem.yLabel).toFixed(0); // Display as integer
                    }
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        min: 0 // Ensure the y-axis starts at 0
                    }
                }]
            }
        }
    };
        const DATE = <?php echo json_encode($dates); ?>;
        const profitData = <?php echo json_encode($profits); ?>;

        // Line Chart Configuration
        const profitChartConfig = {
            type: "line",
            data: {
                labels: DATE,
                datasets: [{
                    label: 'Profit',
                    backgroundColor: "#cccbfd",
                    borderColor: "#adabfc",
                    data: profitData
                }]
            },
            options: {
                legend: { display: true },
                title: {
                    display: true,
                    text: "Profit for Current Month"
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value) { return Number(value).toFixed(0); } 
                        }
                    }]
                }
            }
        };

        // Initialize Charts
        new Chart(document.getElementById("myChart"), barChartConfig);
        new Chart(document.getElementById("myChart2"), profitChartConfig);
        

    </script>

    
</body>

</html>
