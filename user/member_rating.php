<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    session_start();
    include_once("lib/db.php");

    echo '<script>
    alert("Thanks for submit the review!");
    </script>';

    $MemID = $_SESSION["MemberID"];
    $oID = '';
    $sql = "";

    foreach($_POST as $rev=>$value) {
        if (substr($rev,0,5) == 'srate') {

            $review = explode('-', $rev); // $review[1] = OrderID, $review[2] = ProID
            $oID = $review[1];

            $sql.= "(".$MemID.", ".$review[1].", ".$review[2].", ".$_POST[$rev]." ),";

            $query = mysqli_query($db_conn, "SELECT `OrderID` FROM `review_rate` WHERE `OrderID`='$oID'");
            $num = mysqli_num_rows($query);

            if($num > 0)
            {
                $insert_sql = "UPDATE `review_rate` SET `RevRate` = ".$_POST[$rev]." WHERE `OrderID` = ".$review[1]." AND `ProID` = ".$review[2]."";
                mysqli_query($db_conn, $insert_sql);
            }
        }
    }
    $sql = rtrim($sql,',');

    $query = mysqli_query($db_conn, "SELECT `OrderID` FROM `review_rate` WHERE `OrderID`='$oID'");
    $num = mysqli_num_rows($query);

    if($num == 0)
    {
        $insert_sql = "INSERT INTO `review_rate` (`MemberID`, `OrderID`, `ProID`, `RevRate`) VALUES ".$sql."";
        $com_sql = "INSERT INTO `review_comment` (`OrderID`, `Comment`) VALUES (".$oID.", '".$_POST["textreview"]."')";
    }
    else
    {
        $com_sql = "UPDATE `review_comment` SET `Comment` = '" . $_POST["textreview"] . "' WHERE `OrderID` = " . $oID;
    }

    mysqli_query($db_conn, $com_sql);
    mysqli_query($db_conn, $insert_sql);


    Header("Location: member_order.php?OrderID=".$oID."");

}

?>