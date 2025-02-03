<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get total income
$stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS total_income FROM income WHERE user_id = ?");
$stmt->execute([$user_id]);
$income = $stmt->fetch()['total_income'];

// Get total expenses
$stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS total_expenses FROM expenses WHERE user_id = ?");
$stmt->execute([$user_id]);
$expenses = $stmt->fetch()['total_expenses'];

// Calculate balance
$balance = $income - $expenses;

// Display financial summary
echo "<h1>Welcome, " . $_SESSION['user_name'] . "!</h1>";
echo "<h3>Total Income: $$income</h3>";
echo "<h3>Total Expenses: $$expenses</h3>";
echo "<h3>Current Balance: $$balance</h3>";

// Fetch budget limits and check spending
echo "<h2>Budget Overview</h2>";

$stmt = $conn->prepare("SELECT * FROM budgets WHERE user_id = ?");
$stmt->execute([$user_id]);
$budgets = $stmt->fetchAll();

foreach ($budgets as $budget) {
    $category = $budget['category'];
    $limit_amount = $budget['limit_amount'];

    // Get total spent for this category
    $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS total_spent FROM expenses WHERE user_id = ? AND category = ?");
    $stmt->execute([$user_id, $category]);
    $total_spent = $stmt->fetch()['total_spent'];

    echo "<strong>$category:</strong> Budget: $$limit_amount | Spent: $$total_spent";

    // Show alert if over budget
    if ($total_spent > $limit_amount) {
        echo " <span style='color: red; font-weight: bold;'>⚠ Over Budget!</span>";
    }

    echo "<br>";
}

echo "<br><a href='add_income.php'>Add Income</a> | ";
echo "<a href='add_expense.php'>Add Expense</a> | ";
echo "<a href='set_budget.php'>Set Budget</a> | ";
echo "<a href='view_transactions.php'>View Transactions</a> | ";
echo "<a href='logout.php'>Logout</a>";
?>
