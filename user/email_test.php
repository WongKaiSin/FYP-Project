<?php
include("./lib/function.php");

$func = new Functions;

$SiteUrl = "http://localhost:80/FYP-Project";
$PassEmail = "fomab94148@godsigma.com";
$MemberID = "1";


$encodedToken = substr_replace($PassEmail, $MemberID."#".date("His"), 4, 0);

$resetLink = "$SiteUrl/reset-password.php?token=$encodedToken";

$custom_msg = "$resetLink######$PassEmail";


if($func->send_email(2, "test", $PassEmail, $custom_msg))
    echo "Send Succesfullly";
else
    echo "Error! please call kaisin QAQ";

?>