<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p>{$_SESSION['message']}</p>";
        unset($_SESSION['message']);
    }
    ?>
    <form action="send-password-reset.php" method="POST">
        <label for="email">Enter your email:</label><br>
        <input type="MemberEmail" id="MemberEmail" name="MemberEmail" required><br>
        <button type="submit" name="submit">Submit</button>
    </form>
</body>
</html>