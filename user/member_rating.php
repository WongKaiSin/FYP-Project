<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

session_start();
include_once("lib/db.php");

echo '<script>
alert("Thanks for submit the review!");
</script>';

$MemID = $_SESSION["MemberID"];

$oID = '';

$sql = "INSERT INTO `review_rate` (`MemberID`, `OrderID`, `ProID`, `RevRate`) VALUES ";

foreach($_POST as $rev=>$value) {
    
    if (substr($rev,0,5) == 'srate') {

        $review = explode('-', $rev);
        

        $oID = $review[1];

        $sql.= "(".$MemID.", ".$review[1].", ".$review[2].", ".$_POST[$rev]." ),";

    }
    
}

$sql = rtrim($sql,',');


$com_sql = "INSERT INTO review_comment (`OrderID`, `Comment`) VALUES (".$oID.", '".$_POST["textreview"]."')";

mysqli_query($db_conn, $com_sql);
mysqli_query($db_conn, $sql);


Header("Location: member_order.php?OrderID=".$oID."");



}

?>