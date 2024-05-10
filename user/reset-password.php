<?php
session_start();
include("lib/db.php"); // Include database connection

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if token exists in database
    $sql = "SELECT * FROM users WHERE reset_token='$token'";
    $query = $db_conn->query($sql);

    if ($query->num_rows > 0) {
        // Display form to reset password
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="process-reset-password.php" method="POST">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <button type="submit" name="submit">Reset Password</button>
    </form>
</body>
</html>
<?php
    } else {
        $_SESSION['message'] = "Invalid token. Please try again.";
        header("Location: forgot-password.php");
        exit();
    }
} else {
    $_SESSION['message'] = "Token not found. Please request a new reset link.";
    header("Location: forgot-password.php");
    exit();
}
?>