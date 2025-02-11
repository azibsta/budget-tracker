<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['feedback_id'])) {
    $feedback_id = $_POST['feedback_id'];

    $stmt = $conn->prepare("DELETE FROM feedbacks WHERE id = ?");
    if ($stmt->execute([$feedback_id])) {
        $_SESSION['message'] = "✅ Feedback deleted successfully!";
    } else {
        $_SESSION['message'] = "❌ Error deleting feedback.";
    }
}

header("Location: admin_dashboard.php");
exit();
