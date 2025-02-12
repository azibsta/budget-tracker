<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_id = $_POST['request_id'];

    $stmt = $conn->prepare("DELETE FROM support_tickets WHERE id = ?");
    if ($stmt->execute([$request_id])) {
        header("Location: ../admin_dashboard.php");
    } else {
        echo '<div class="alert alert-danger text-center">Error: Could not delete support request.</div>';
    }
}
?>