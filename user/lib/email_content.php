<?php
$id = isset($_GET["id"]) ? $_GET["id"] : [];

if ($id == 1) 
{
    $EmailUserSubject = 'Welcome to {#SiteName}';

    $EmailUserMsg = '<h3>Account Created</h3>
    You may login to your account now at <a href="http://localhost:80/FYP-Project/user/registration.php">here</a> with following information:<br />
    <br />
    Email Address : {#MemberEmail}<br />
    Password : {#MemberPassword}';
}

if ($id == 2) 
{
    $EmailUserSubject = '{#SiteName} - Forgot your password?';

    // $EmailUserMsg = 
    echo '<h3>Retrieve Password</h3>

    <p><a href="{#link}">Click here</a> to retrieve your password for email address, {#email}.<br />
    The link is only valid for 20 minutes.</p>';
}
?>
