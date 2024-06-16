<?php
session_start();
include("lib/db.php");
require_once("lib/function.php");

$func = new Functions;
$msg = (isset($_GET['msg']) ? $_GET["msg"] : "");

$SiteUrl = "http://localhost:80/FYP-Project";

if(isset($_SESSION['MemberID'])) {
    $MemberID = $_SESSION['MemberID'];

    $sql = "SELECT `OrderID`, `OrderNo`, `OrderType`, `OrderSubtotal`, `OrderShipping`, `OrderTotal`, `OrderStatus`, `PaymentID`
    FROM `order` WHERE `MemberID` = ? ORDER BY `OrderID` DESC" ;
    $stmt = $db_conn->prepare($sql);
    $stmt->bind_param("i", $MemberID);
    $stmt->execute();
    $result = $stmt->get_result();

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("lib/head.php"); ?>
  <title>Order History | London Bagel Museum</title>

</head>
<body>
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">
      <?php 
        include("lib/logo.php");
        include("lib/topmenu.php");
      ?>
    </div>
  </header>

  <main id="main">
    <div class="breadcrumbs">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center">
          <h2>Order History</h2>
          <ol>
            <li><a href="index.php">Home</a></li>
            <li>Order History</li>
          </ol>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-lg-3">
          <?php include("lib/sidebar.php"); ?>
        </div>
        <div class="col-lg-9">
          <section id="orderlist" class="orderlist">
            <div class="container" data-aos="fade-up">
                <h3><span>Your Order History</span></h3>
                <div class="card-body">
                    <p>
                      <i class="m-r-10 fas fa-stopwatch"> Preparing</i>
                      <i class="m-r-10 fas fa-shipping-fast"> Shipping</i>
                      <i class="m-r-10 fas fa-dolly"> Complete</i>
                      <i class="m-r-10 fas fa-times"> Cancel</i>
                    </p>
                    <div id="product-display" class="product-display">
                    <?php
                      if ($result->num_rows > 0)
                      {
                          while($row = $result->fetch_assoc())
                          {
                            $OrderID = $row['OrderID'];
                            $OrderNo = $row['OrderNo'];
                            $OrderType = $row['OrderType'];
                            $OrderSubtotal = $row['OrderSubtotal'];
                            $OrderShipping = $row['OrderShipping'];
                            $OrderTotal = $row['OrderTotal'];
                            $OrderStatus = $row['OrderStatus'];
                            $PaymentID = $row['PaymentID'];

                            $OrderName="";
                            if($OrderType == 0)
                              $OrderName = "Delivery";
                            else
                              $OrderName = "Take away";

                            $pay_sql = "SELECT `PaymentName` FROM `payment` WHERE `PaymentID` = ?";
                            $pay_stmt = $db_conn->prepare($pay_sql);
                            $pay_stmt->bind_param("i", $PaymentID);
                            $pay_stmt->execute();
                            $pay_stmt->bind_result($PaymentName);
                            $pay_stmt->fetch();
                            $pay_stmt->close(); 

                            // echo '<button type="button" class="btn btn-outline-secondary product-item" data-bs-toggle="modal" data-bs-target="#exampleModalToggle_'.$OrderID.'">
                            echo '<button type="button" class="btn product-item mb-3" data-bs-toggle="modal" data-bs-target="#exampleModalToggle_'.$OrderID.'">
                                      <p class="mt-2">'.$OrderNo.'</p>
                                      <p><span><b>'.$OrderStatus.'</b></span></p>';

                            $rate_sql = "SELECT AVG(`RevRate`) AS `avg_rate` FROM `review_rate` WHERE `OrderID` = ?";
                            $rate_stmt = $db_conn->prepare($rate_sql);
                            $rate_stmt->bind_param("i", $OrderID);
                            $rate_stmt->execute();
                            $rate_result = $rate_stmt->get_result();

                            if($rate_result && $rate_row = $rate_result->fetch_assoc())
                            {
                              $avg_rate = $rate_row['avg_rate'];

                              if ($avg_rate !== null)
                              {
                                echo '<div class="star-rating-container">';
                                echo $func->displayStars($avg_rate);
                                echo '</div>';
                              }

                              else
                              {
                                echo '<span>No rating available</span>';
                              }
                            }
                            else
                            {
                                echo '<span>No rating available</span>';
                            }

                            $rate_stmt->close();

                            echo '</button>

                            <div class="modal fade" id="exampleModalToggle_'.$OrderID.'" tabindex="-1" aria-labelledby="exampleModalToggleLabel_'.$OrderID.'" aria-hidden="true">
                              <div class="modal-dialog modal-xl modal-dialog-centered">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h1 class="modal-title fs-5" id="exampleModalToggleLabel_'.$OrderID.'">'.$OrderNo.' ('.$OrderStatus.')</h1>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">';

                              echo '<table class="table table-hover">
                                      <thead>
                                          <tr>
                                              <th scope="col">#</th>
                                              <th scope="col">Food Name</th>
                                              <th scope="col">Price</th>
                                              <th scope="col">Quantity</th>
                                              <th scope="col">SubTotal</th>
                                              <th scope="col">Rating</th>
                                          </tr>
                                      </thead>
                                      <tbody>';

                              $com_sql = "SELECT `ComRevID`, `Comment`, `Approval` FROM `review_comment` WHERE `OrderID` = ?";
                              $com_stmt = $db_conn->prepare($com_sql);
                              $com_stmt->bind_param("i", $OrderID);
                              $com_stmt->execute();
                              $com_result = $com_stmt->get_result(); // get result
                              $com_row = $com_result->fetch_assoc(); // Get the row

                              if ($com_row !== null)
                              {
                                  $ComRevID = $com_row["ComRevID"];
                                  $Approval = $com_row["Approval"];

                                  if($Approval == 0)
                                    $OrderCom = "Waiting...";
                                  else
                                    $OrderCom = $com_row["Comment"];
                              }
                              
                              else
                              {
                                  $ComRevID = null;
                                  $OrderCom = "Haven't comment yet.";
                                  $Approval = null;
                              }
                              $com_stmt->close(); 

                              $pro_sql = "SELECT `ProID`, `ProName`, `ProUrl`, `ProPrice`, `ProQty`, `ProTotal`
                              FROM `order_product` WHERE `OrderID` = ?";
                              $pro_stmt = $db_conn->prepare($pro_sql);
                              $pro_stmt->bind_param("i", $OrderID);
                              $pro_stmt->execute();
                              $pro_result = $pro_stmt->get_result();
                              $counter = 1;

                              while($pro_row = $pro_result->fetch_assoc())
                              {
                                $ProID = $pro_row['ProID'];
                                $ProName = $pro_row['ProName'];
                                $ProUrl = $pro_row['ProUrl'];
                                $ProPrice = $pro_row['ProPrice'];
                                $ProQty = $pro_row['ProQty'];
                                $ProTotal = $pro_row['ProTotal'];

                                $prate_sql = "SELECT `RevRate` FROM `review_rate` WHERE `ProID` = ? AND `OrderID` = ?";
                                $prate_stmt = $db_conn->prepare($prate_sql);

                                if($prate_stmt)
                                {
                                    $prate_stmt->bind_param("ii", $ProID, $OrderID);
                                    $prate_stmt->execute();
                                    $prate_result = $prate_stmt->get_result();

                                    if($prate_result && $prate_row = $prate_result->fetch_assoc())
                                    {
                                        $rate = $prate_row["RevRate"];
                                    }
                                    else {
                                        $rate = NULL;
                                    }

                                    $prate_stmt->close();
                                }
                                
                                else {
                                    $rate = NULL;
                                }

                                echo "<tr>
                                        <td>".$counter."</td>
                                        <td>".$ProName."</td>
                                        <td>".$ProPrice."</td>
                                        <td>".$ProQty."</td>
                                        <td>".$ProTotal."</td>
                                        <td>
                                          <div class='star-rating-container'>";
                                            if ($rate !== null){
                                              echo $func->displayStars($rate);
                                            }
              
                                            else{
                                              echo '<span>No rating available</span>';
                                            }
                                    echo "</div>
                                        </td>
                                      </tr>";
                                $counter++;
                              }

                                echo '</tbody>
                                    </table>
                                  </div>

                                  <div class=" total-box">
                                    <table class="float-start">
                                      <thead>
                                        <tr>
                                          <th>Pay Via</th>
                                        </tr>
                                        <tr>
                                          <td>'.$PaymentName.'</td>
                                        </tr>
                                        <tr>
                                          <th>Service Type</th>
                                        </tr>
                                        <tr>
                                          <td>'.$OrderName.'</td>
                                        </tr>
                                        <tr>
                                          <th>Review Comment</th>
                                        </tr>
                                        <tr>
                                          <td>'.$OrderCom.'</td>
                                        </tr>
                                      </thead>
                                    </table>
                                    <table class="table-listing table-item float-end">
                                      <thead>
                                        <th>
                                            Total
                                        </th>
                                      </thead>
                                      <tbody>
                                        <tr class="total-row">
                                            <td colspan="4" class="text-right">Subtotal</td>
                                            <td class="text-right"><strong><span class="d-lg-none">RM </span>'.$OrderSubtotal.'</strong></td>
                                        </tr>
                                        <tr class="total-row">
                                            <td colspan="4" class="text-right">Shipping Fee</td>
                                            <td class="text-right"><span class="d-lg-none">RM </span>'.$OrderShipping.'</td>
                                        </tr>
                                        <tr class="total-row">
                                            <td colspan="4" class="text-right"><strong>Total</strong></td>
                                            <td class="text-right"><strong><span class="d-lg-none">RM </span>'.$OrderTotal.'</strong></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                  <div class="modal-footer" style="justify-content: center">';
                                  if($OrderType == 0)
                                  {
                                    echo '<button type="button" class="btn btn-primary orderButton" data-bs-target="#exampleModalToggle3_'.$OrderID.'" data-bs-toggle="modal">Check Address</button>';
                                  }
                                  
                                  if($Approval == NULL)
                                  {
                                    echo '<button type="button" class="btn btn-primary orderButton" data-bs-target="#exampleModalToggle2_'.$OrderID.'" data-bs-toggle="modal">Make Review</button>';
                                  }

                               echo '<button type="button" class="btn btn-secondary orderButton" data-bs-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="modal fade" id="exampleModalToggle2_'.$OrderID.'" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2_'.$OrderID.'" tabindex="-1">
                              <div class="modal-dialog modal-xl modal-dialog-centered">
                                <div class="modal-content">

                                  <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalToggleLabel2_'.$OrderID.'">Review Submission</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <form action="member_rating.php" method="post">
                                  ';
                                  
                                  
                                  
                                  echo '<table class="table table-hover">
                                      <thead>
                                          <tr>
                                              <th scope="col">#</th>
                                              <th scope="col">Food Name</th>
                                              <th scope="col">Rating</th>
                                          </tr>
                                      </thead>
                                      <tbody>';
                              
                              $pro_sql = "SELECT `ProID`, `ProName`, `ProUrl`, `ProPrice`, `ProQty`, `ProTotal`
                              FROM `order_product` WHERE `OrderID` = ?";
                              $pro_stmt = $db_conn->prepare($pro_sql);
                              $pro_stmt->bind_param("i", $OrderID);
                              $pro_stmt->execute();
                              $pro_result = $pro_stmt->get_result();
                              $counter = 1;

                              while($pro_row = $pro_result->fetch_assoc())
                              {
                                $ProID = $pro_row['ProID'];
                                $ProName = $pro_row['ProName'];
                                $ProUrl = $pro_row['ProUrl'];
                                $ProPrice = $pro_row['ProPrice'];
                                $ProQty = $pro_row['ProQty'];
                                $ProTotal = $pro_row['ProTotal'];

                                $prate_sql = "SELECT `RevRate` FROM `review_rate` WHERE `ProID` = ? AND `OrderID` = ?";
                                $prate_stmt = $db_conn->prepare($prate_sql);

                                if($prate_stmt)
                                {
                                    $prate_stmt->bind_param("ii", $ProID, $OrderID);
                                    $prate_stmt->execute();
                                    $prate_result = $prate_stmt->get_result();

                                    if ($prate_result && $prate_result->num_rows > 0)
                                    {
                                        $prate_row = $prate_result->fetch_assoc();
                                        $rate = $prate_row["RevRate"];
                                    }

                                    $prate_stmt->close();
                                }
                                
                                else {
                                    $rate = 0;
                                }

                                echo "<tr>
                                        <td>".$counter."</td>
                                        <td>".$ProName."</td>
                                        <td>
                                          <div class='star-rating-container'>
                                            <div class='star-rating'>";

                                              for ($i = 1; $i <= 5; $i++) {
                                                $checked = ($i == $rate) ? "checked" : "";
                                                echo "<div class='form-check form-check-inline'>
                                                        <input class='form-check-input star-rating-rb' type='radio' name='srate-{$OrderID}-{$ProID}' id='srate{$i}_{$OrderID}{$ProID}' value='{$i}' {$checked}/>
                                                        <label class='form-check-label' for='srate{$i}_{$OrderID}{$ProID}'>";
                                                        for($x = 1; $x<=$i; $x++){
                                                          echo "★";
                                                        }
                                                  echo "</label>
                                                      </div>";
                                              }

                                      echo" </div>
                                          </div>
                                        </td>
                                      </tr>";

                                $counter++;
                              }
                            echo '    </tbody>
                                    </table>
                                    Review Comment:<br>
                                    <textarea style="width: 100%;" id="textreview" name="textreview" rows="5" placeholder="Write your comment."></textarea>
                                    <button class="btn btn-primary orderButton float-end">Submit Review</button>
                                  </form>

                                  </div>

                                  <div class="modal-footer">
                                    <button class="btn btn-primary orderButton float-start" data-bs-target="#exampleModalToggle_'.$OrderID.'" data-bs-toggle="modal">Back</button>
                                  </div>
                               
                                </div>
                              </div>
                            </div>';

                      echo '<div class="modal fade" id="exampleModalToggle3_'.$OrderID.'" tabindex="-1" aria-labelledby="exampleModalToggleLabel3_'.$OrderID.'" aria-hidden="true">
                              <div class="modal-dialog modal-xl modal-dialog-centered">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h1 class="modal-title fs-5" id="exampleModalToggleLabel3_'.$OrderID.'">'.$OrderNo.' ('.$OrderStatus.')</h1>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">';
                              if(isset($msg))
                              {
                                  echo "<h6>Thank you For Ordering! Please Wait For a While...</h6>";
                              }


                              echo '<table class="table table-hover">
                                      <thead>
                                          <tr>
                                              <th scope="col">#</th>
                                              <th scope="col">Food Name</th>
                                              <th scope="col">Price</th>
                                              <th scope="col">Quantity</th>
                                              <th scope="col">SubTotal</th>
                                          </tr>
                                      </thead>
                                      <tbody>';

                              $add_sql = "SELECT `AddName`, `AddPhone`, `AddAddress`, `AddPostcode`, `AddCity`, `AddState`, `AddCountry`  
                              FROM `member_address` WHERE `MemberID` = ?";
                              $add_stmt = $db_conn->prepare($add_sql);
                              $add_stmt->bind_param("i", $MemberID);
                              $add_stmt->execute();
                              $add_result = $add_stmt->get_result();
                              $add_row = $add_result->fetch_assoc();

                              $Address = "".$add_row["AddAddress"].", ".$add_row["AddPostcode"].", ".$add_row["AddCity"].", ".$add_row["AddState"].", ".$add_row["AddCountry"].".";
                              $Name = $add_row["AddName"];
                              $Phone = $add_row["AddPhone"];

                              $pro_sql = "SELECT `ProID`, `ProName`, `ProUrl`, `ProPrice`, `ProQty`, `ProTotal`
                              FROM `order_product` WHERE `OrderID` = ?";
                              $pro_stmt = $db_conn->prepare($pro_sql);
                              $pro_stmt->bind_param("i", $OrderID);
                              $pro_stmt->execute();
                              $pro_result = $pro_stmt->get_result();
                              $counter = 1;

                              while($pro_row = $pro_result->fetch_assoc())
                              {
                                $ProID = $pro_row['ProID'];
                                $ProName = $pro_row['ProName'];
                                $ProUrl = $pro_row['ProUrl'];
                                $ProPrice = $pro_row['ProPrice'];
                                $ProQty = $pro_row['ProQty'];
                                $ProTotal = $pro_row['ProTotal'];

                                echo "<tr>
                                        <td>".$counter."</td>
                                        <td>".$ProName."</td>
                                        <td>".$ProPrice."</td>
                                        <td>".$ProQty."</td>
                                        <td>".$ProTotal."</td>
                                      </tr>";
                                $counter++;
                              }

                                echo '</tbody>
                                    </table>
                                  </div>

                                  <div class=" total-box">
                                    <table class="float-start">
                                      <thead>
                                        <tr>
                                          <th>Pay Via</th>
                                        </tr>
                                        <tr>
                                          <td>'.$PaymentName.'</td>
                                        </tr>
                                        <tr>
                                          <th>Service Type</th>
                                        </tr>
                                        <tr>
                                          <td>'.$OrderName.'</td>
                                        </tr>
                                        <tr>
                                          <th>Recipient Information</th>
                                        </tr>
                                        <tr>
                                          <td>'.$Name.'</td>
                                        </tr>
                                        <tr>
                                          <td>'.$Phone.'</td>
                                        </tr>
                                        <tr>
                                          <td>'.$Address.'</td>
                                        </tr>
                                      </thead>
                                    </table>
                                    <table class="table-listing table-item float-end">
                                      <thead>
                                        <th>
                                            Total
                                        </th>
                                      </thead>
                                      <tbody>
                                        <tr class="total-row">
                                            <td colspan="4" class="text-right">Subtotal</td>
                                            <td class="text-right"><strong><span class="d-lg-none">RM </span>'.$OrderSubtotal.'</strong></td>
                                        </tr>
                                        <tr class="total-row">
                                            <td colspan="4" class="text-right">Shipping Fee</td>
                                            <td class="text-right"><span class="d-lg-none">RM </span>'.$OrderShipping.'</td>
                                        </tr>
                                        <tr class="total-row">
                                            <td colspan="4" class="text-right"><strong>Total</strong></td>
                                            <td class="text-right"><strong><span class="d-lg-none">RM </span>'.$OrderTotal.'</strong></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                  <div class="modal-footer" style="justify-content: center">
                                  <button type="button" class="btn btn-primary orderButton" data-bs-target="#exampleModalToggle_'.$OrderID.'" data-bs-toggle="modal">Back</button>';

                                  if($Approval == NULL)
                                  {
                                    echo '<button type="button" class="btn btn-primary orderButton" data-bs-target="#exampleModalToggle2_'.$OrderID.'" data-bs-toggle="modal">Make Review</button>';
                                  }

                            echo '</div>
                                </div>
                              </div>
                            </div>';
                          }
                      }
                                  
                      else {
                        echo "0 results";
                      }

                      if($OrderID = (isset($_GET['OrderID']) ? $_GET["OrderID"] : ""))
                      {
                        echo "<script>
                                $(document).ready(function() {
                                    // Automatically show the modal when the page loads
                                    $('#exampleModalToggle3_$OrderID').modal('show');

                                });
                              </script>";
                      }
                    ?>
                    </div>
                </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </main>
  <?php include("lib/footer.php"); ?>
  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <div id="preloader"></div>
</body>
</html>
