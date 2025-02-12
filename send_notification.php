<?php
session_start();
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = trim($_POST['message']);

    if (!empty($message)) {
        // Insert as a broadcast notification
        $stmt = $conn->prepare("INSERT INTO notifications (user_id, message, is_broadcast) VALUES (?, ?, ?)");
        $stmt->execute([null, $message, 1]); // Use null for broadcasts to indicate it's sent to all users
        
        $_SESSION['notify_message'] = "✅ Notification sent to all users!";
    } else {
        $_SESSION['notify_message'] = "❌ Message cannot be empty!";
    }
}

header("Location: admin_notifications.php");
exit();
