<?php
include("lib/db.php");
include("lib/function.php"); // Corrected the file name

$func = new Functions();

$email = "wong"; // Change to a valid email
$pass = "kai"; // Change to a valid password

$done = $func->PassSign($email, $pass);

// var_dump('location: '.$_SERVER['DOCUMENT_ROOT']."/FYP-Project/admin/login.php");
// header("location: ../user/menu-info.php");

echo basename($_SERVER["SCRIPT_FILENAME"])."<br>";
echo $_SERVER['DOCUMENT_ROOT']."/FYP-Project/<br>";

echo "Email: ". $email."<br>";
echo "Pass: ".$pass."<br>";
echo "Hashed password: ".$done;
?>

<script>location="<?$_SERVER['DOCUMENT_ROOT']."/FYP-Project/"?>"</script>
