<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$user_id])) {
        $_SESSION['message'] = "✅ User deleted successfully!";
    } else {
        $_SESSION['message'] = "❌ Error deleting user.";
    }
}

header("Location: admin_dashboard.php");
exit();
