<?php
session_start();
require 'config/db.php';

if (!isset($_GET['token'])) {
    die("<div class='alert alert-danger text-center'>❌ Invalid request!</div>");
}

$token = $_GET['token'];

// Check if the token exists in the database
$stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    die("<div class='alert alert-danger text-center'>❌ Invalid token!</div>");
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Update password and clear the reset token
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
    if ($stmt->execute([$newPassword, $token])) {
        $message = "<div class='alert alert-success text-center'>✅ Password reset successfully! <a href='login.php'>Login here</a></div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>❌ Error resetting password.</div>";
    }
}

include 'header.php';
?>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4 bg-white rounded" style="width: 400px;">
        <h2 class="text-center mb-4">Reset Password</h2>

        <?= $message ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Reset Password</button>
        </form>

        <div class="text-center mt-3">
            <a href="login.php">Back to Login</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
