<?php

// Initialize variables to avoid undefined variable notices
$EmailUserSubject = '';
$EmailUserMsg = '';

// Retrieve the email_id from the session or default to an empty string
$email_id = isset($_SESSION["email_id"]) ? $_SESSION["email_id"] : '';

// Set email content based on email_id
if ($email_id == 1) {
    $EmailUserSubject = 'Welcome to {#SiteName}';
    $EmailUserMsg = '<h3>Account Created</h3>
    You may login to your account now at <a href="admin/login.php">here</a> with the following information:<br />
    <br />
    Email Address : {#AdminUserName}<br />
    Password : {#AdminPassword}';
} elseif ($email_id == 2) {
    $EmailUserSubject = '{#SiteName} - Forgot your password?';
    $EmailUserMsg = '<h3>Reset Password</h3>
    <p>This is the new password, <strong>{#new_pass}</strong> for User, {#adUser} to login and please change the password.<br />';
}
?>
