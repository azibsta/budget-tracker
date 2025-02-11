<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'], $_POST['role'])) {
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    if ($stmt->execute([$role, $user_id])) {
        $_SESSION['message'] = "✅ User role updated!";
    } else {
        $_SESSION['message'] = "❌ Error updating role.";
    }
}

header("Location: admin_dashboard.php");
exit();
