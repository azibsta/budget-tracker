<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category'])) {
    $user_id = $_SESSION['user_id'];
    $category = $_POST['category'];

    try {
        $conn->beginTransaction();

        // Delete all expenses related to this category
        $stmt = $conn->prepare("DELETE FROM expenses WHERE user_id = ? AND category = ?");
        $stmt->execute([$user_id, $category]);

        // Delete the budget entry
        $stmt = $conn->prepare("DELETE FROM budgets WHERE user_id = ? AND category = ?");
        $stmt->execute([$user_id, $category]);

        $conn->commit();
        $_SESSION['message'] = "✅ Budget and related expenses deleted successfully!";
    } catch (Exception $e) {
        $conn->rollBack();
        $_SESSION['message'] = "❌ Error: Unable to delete budget.";
    }
}

header("Location: dashboard.php");
exit();
