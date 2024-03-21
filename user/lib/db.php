<?php
$dbName = 'bagel';
$dbServer = 'localhost';
$dbUser = 'root';
$dbPass = '';

$db_conn = mysqli_connect($dbServer, $dbUser, $dbPass, $dbName) or die("Connection not available.");
mysqli_query($db_conn, "SET NAMES utf8") or die("Set names wrong.");

date_default_timezone_set('Asia/Kuala_Lumpur');
?>