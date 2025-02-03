<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

echo "<h1>Transaction History</h1>";

// Fetch income
echo "<h2>Income</h2>";
$stmt = $conn->prepare("SELECT * FROM income WHERE user_id = ? ORDER BY date DESC");
$stmt->execute([$user_id]);
$incomes = $stmt->fetchAll();
foreach ($incomes as $income) {
    echo "💰 {$income['amount']} from {$income['source']} on {$income['date']}<br>";
}

// Fetch expenses
echo "<h2>Expenses</h2>";
$stmt = $conn->prepare("SELECT * FROM expenses WHERE user_id = ? ORDER BY date DESC");
$stmt->execute([$user_id]);
$expenses = $stmt->fetchAll();
foreach ($expenses as $expense) {
    echo "💸 {$expense['amount']} spent on {$expense['category']} on {$expense['date']}<br>";

    // Get budget for this category
    $stmt = $conn->prepare("SELECT limit_amount FROM budgets WHERE user_id = ? AND category = ?");
    $stmt->execute([$user_id, $expense['category']]);
    $budget = $stmt->fetch();

    if ($budget) {
        $budget_limit = $budget['limit_amount'];
        if ($expense['amount'] > $budget_limit) {
            echo "<span style='color: red;'>⚠ Over Budget!</span>";
        }
    }

    echo "<br>";
}

echo "<br><a href='dashboard.php'>Back to Dashboard</a>";
?>
