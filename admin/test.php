<?php
include("lib/db.php");
include("lib/function.php"); // Corrected the file name

$func = new Functions();

$sql = "SELECT ProID, ProName, ProUrl FROM `product`";
$query = $db_conn -> query($sql);

while($row = $query->fetch_assoc())
{
    $ProID = $row["ProID"];
    $ProName = $row["ProName"];
    $ProUrl = $row["ProUrl"];

    echo $ProID." ".$ProName." ".$ProUrl."<br>";

    if(isset($ProUrl))
    {
        $ProUrl = strtolower($func->convertToURL($ProName));
        echo $ProUrl."<br>";
    }

    $add = "UPDATE product SET ProUrl=? WHERE ProID=?";
    $stmt = $db_conn->prepare($add);

    // Bind parameters to the placeholders
    $stmt->bind_param("si", $ProUrl, $ProID);  // "si" means string and integer

    // Execute the statement
    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();

}
