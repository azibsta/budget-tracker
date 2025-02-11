<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Get user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verify password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php"); // Redirect admins to admin panel
        } else {
            header("Location: dashboard.php"); // Redirect users to normal dashboard
        }
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}

include 'header.php';
?>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4 bg-white rounded" style="width: 400px;">
        <h2 class="text-center mb-4">Login</h2>
        <?php if (isset($error)) { echo '<div class="alert alert-danger text-center">' . $error . '</div>'; } ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <div class="text-center mt-3">
            <a href="forgot_password.php">Forgot Password?</a> | 
            <a href="register.php">Register</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
