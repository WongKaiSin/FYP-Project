<?php
session_start();
require("db.php");

if (isset($_GET['logout']))
{	
    $_SESSION['AdminLogged'] = false;
	unset($_SESSION["AdminID"]);
	unset($_SESSION["LastLink"]);
   
	// echo "<script>self.location='index.php';</script>"; 
	exit;
}

if(!isset($_SESSION['AdminLogged']) || $_SESSION['AdminLogged'] !== true)
{
	$_SESSION["LastLink"] = $_SERVER["REQUEST_URI"];
	
	// echo "<script>self.location='login.php';</script>"; 
	exit;
}

$AdminID = $_SESSION['adID'];
$AdminUser = $_SESSION['adUser'];
$AdminName = $_SESSION['adName'];
$AdminLogo = $_SESSION['AdLogo'];
// $AdminMenu = $_SESSION['adMenu'];
$AdminMenu = !empty($AdminCmsMenu) ? explode(", ", $AdminCmsMenu) : "";	 // will be array
$FileName = basename($_SERVER["SCRIPT_FILENAME"]);
$UploadPath = $_SERVER['DOCUMENT_ROOT']."/uploads/";
$CurrDate = date("Y-m-d");
$CurrDateTime = date("Y-m-d H:i:s");

?>