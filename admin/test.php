<?php
include("lib/db.php");
include("lib/function.php"); // Corrected the file name

$func = new Functions();

$email = "wong"; // Change to a valid email
$pass = "kai"; // Change to a valid password

$done = $func->PassSign($email, $pass);

echo "Email: ". $email."<br>";
echo "Pass: ".$pass."<br>";
echo "Hashed password: ".$done;
?>
