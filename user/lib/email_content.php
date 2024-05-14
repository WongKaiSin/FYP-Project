<?php
$email_id = $_SESSION["email_id"];

if (!isset($email_id)) {
    $email_id = ''; // Default to an empty string if not set
}

if ($email_id == 1) 
{
    $EmailUserSubject = 'Welcome to {#SiteName}';

    $EmailUserMsg = '<h3>Account Created</h3>
    You may login to your account now at <a href="http://localhost:80/FYP-Project/user/registration.php">here</a> with following information:<br />
    <br />
    Email Address : {#MemberEmail}<br />
    Password : {#MemberPassword}';
}

if ($email_id == 2) 
{
    $EmailUserSubject = '{#SiteName} - Forgot your password?';

    $EmailUserMsg = '<h3>Retrieve Password</h3>

    <p><a href="{#link}">Click here</a> to retrieve your password for email address, {#email}.<br />
    The link is only valid for 20 minutes.</p>';
}
?>
