<?php
include("./lib/function.php");

$func = new Functions;

$SiteUrl = "http://localhost:80/FYP-Project";
$PassEmail = "cewidov879@huleos.com";
$MemberID = "1";


$encodedToken = substr_replace($PassEmail, $MemberID."#".date("His"), 4, 0);

$resetLink = "$SiteUrl/reset-password.php?token=$encodedToken";

$custom_msg = "$resetLink######$PassEmail";

$func->send_email(2, "test", $PassEmail, $custom_msg)

// echo $PassEmail;
// echo "Send Succesfullly";


?>