<?php
session_start();
include("lib/db.php"); // Include database connection

if (isset($_POST['submit'])) {
    $MemberEmail = $_POST['MemberEmail'];
    
    // Check if email exists in database
    $sql = "SELECT * FROM member WHERE MemberEmail='$MemberEmail'";
    $query = $db_conn->query($sql);

    if ($query->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));

        // Save token to database
        $updateSql = "UPDATE users SET reset_token='$token' WHERE MemberEmail='$MemberEmail'";
        $db_conn->query($updateSql);

        // Send email with reset link
        $resetLink = "http://yourwebsite.com/reset-password.php?token=$token";
        $to = $MemberEmail;
        $subject = "Password Reset";
        $message = "Click the link below to reset your password:\n\n$resetLink";
        $headers = "From: your@example.com" . "\r\n" .
                   "Reply-To: your@example.com" . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        if (mail($to, $subject, $message, $headers)) {
            $_SESSION['message'] = "Password reset link sent to your email.";
        } else {
            $_SESSION['message'] = "Failed to send reset link. Please try again later.";
        }
    } else {
        $_SESSION['message'] = "Email not found. Please enter a valid email address.";
    }
    header("Location: forgot-password.php");
    exit();
}
?>