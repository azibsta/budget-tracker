<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $category = $_POST['category'];
    $limit_amount = $_POST['limit_amount'];

    // Check if budget already exists for this category
    $stmt = $conn->prepare("SELECT * FROM budgets WHERE user_id = ? AND category = ?");
    $stmt->execute([$user_id, $category]);
    $existing_budget = $stmt->fetch();

    if ($existing_budget) {
        // Update budget limit
        $stmt = $conn->prepare("UPDATE budgets SET limit_amount = ? WHERE user_id = ? AND category = ?");
        $stmt->execute([$limit_amount, $user_id, $category]);
        echo "Budget updated successfully!";
    } else {
        // Insert new budget limit
        $stmt = $conn->prepare("INSERT INTO budgets (user_id, category, limit_amount) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $category, $limit_amount]);
        echo "Budget set successfully!";
    }
}
?>

<h2>Set Budget Limit</h2>
<form method="POST">
    Category: <input type="text" name="category" required><br>
    Limit Amount: <input type="number" name="limit_amount" step="0.01" required><br>
    <button type="submit">Set Budget</button>
</form>
<a href="dashboard.php">Back to Dashboard</a>
