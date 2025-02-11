<?php
require 'db.php';

// Function to trigger an alert
function createAlert($user_id, $message) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->execute([$user_id, $message]);
}

// ✅ Check if any budget is exceeded (Using User-Defined Budget Limits)
function checkBudgetAlerts($user_id) {
    global $conn;
    
    // Fetch user-set budget limits
    $stmt = $conn->prepare("SELECT category, limit_amount FROM budgets WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $budgets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($budgets as $budget) {
        $category = $budget['category'];
        $limit_amount = $budget['limit_amount'];

        // Fetch total spent by the user in this category
        $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS total_spent FROM expenses WHERE user_id = ? AND category = ?");
        $stmt->execute([$user_id, $category]);
        $total_spent = $stmt->fetch()['total_spent'] ?? 0;

        // ✅ Trigger alert **ONLY** if user-set budget limit is exceeded
        if ($total_spent > $limit_amount) {
            createAlert($user_id, "⚠️ Budget limit exceeded for '{$category}'! Your limit: \${$limit_amount}, Spent: \${$total_spent}");
        }
    }
}

// ✅ Check if any single expense is very high (User can set this threshold)
function checkHighExpenseAlerts($user_id) {
    global $conn;
    
    // Fetch user-defined high expense threshold (Assume default is $500 if not set)
    $stmt = $conn->prepare("SELECT high_expense_limit FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $threshold = $stmt->fetch()['high_expense_limit'] ?? 500; // Default to $500 if not set

    // Fetch the latest expense above the threshold
    $stmt = $conn->prepare("SELECT category, amount FROM expenses WHERE user_id = ? AND amount > ? ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$user_id, $threshold]);
    $expense = $stmt->fetch();

    if ($expense) {
        createAlert($user_id, "⚠️ Large expense detected in '{$expense['category']}'! Amount: \${$expense['amount']} (Your limit: \${$threshold})");
    }
}

// ✅ Run all alert checks
function runAlerts($user_id) {
    checkBudgetAlerts($user_id);
    checkHighExpenseAlerts($user_id);
}
?>
