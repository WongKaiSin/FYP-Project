<?php
require_once("lib/db.php");
$action = $_GET["action"];

if($action == "stock")
{
    $ProID = $_GET["ProID"];

    if(empty($ProID))
        $ProID = 1;

    $pro_sql = "SELECT * FROM product WHERE `ProID` = '".$ProID."'";
    $pro_query = $db_conn ->query($pro_sql);

    if($pro_query->num_rows > 0)
    {
        $row = $pro_query->fetch_assoc();
        $ProStock = $row["ProStock"];
    }
    echo json_encode($ProStock);
}
?>