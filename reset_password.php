<?php
session_start();
require 'config/db.php';

if (!isset($_GET['token'])) {
    die("❌ Invalid request!");
}

$token = $_GET['token'];

// Check if the token exists in the database
$stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    die("❌ Invalid token!");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Update password and clear the reset token
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
    $stmt->execute([$newPassword, $token]);

    echo "✅ Password reset successfully! <a href='login.php'>Login here</a>";
}
?>

<h2>Reset Password</h2>
<form method="POST">
    New Password: <input type="password" name="password" required><br>
    <button type="submit">Reset Password</button>
</form>
