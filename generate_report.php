<?php
if (!isset($reportType) || !isset($startDate) || !isset($endDate)) {
    exit();
}

// User Activity Report
if ($reportType == 'user_activity') {
    echo "<h3 class='mt-4'>User Activity Report</h3>";
    $stmt = $conn->prepare("SELECT name, email, role, created_at FROM users WHERE created_at BETWEEN ? AND ?");
    $stmt->execute([$startDate, $endDate]);
    $users = $stmt->fetchAll();

    echo "<table class='table table-bordered'>";
    echo "<thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Registered On</th></tr></thead><tbody>";
    foreach ($users as $user) {
        echo "<tr><td>{$user['name']}</td><td>{$user['email']}</td><td>{$user['role']}</td><td>{$user['created_at']}</td></tr>";
    }
    echo "</tbody></table>";
}

// ✅ Income Report (Fixing the error)
if ($reportType == 'income') {
    echo "<h3 class='mt-4'>Income Report</h3>";
    $stmt = $conn->prepare("SELECT source, amount, created_at FROM income WHERE created_at BETWEEN ? AND ?");
    $stmt->execute([$startDate, $endDate]);
    $income = $stmt->fetchAll();

    echo "<table class='table table-bordered'>";
    echo "<thead><tr><th>Source</th><th>Amount</th><th>Date</th></tr></thead><tbody>";
    foreach ($income as $inc) {
        echo "<tr><td>{$inc['source']}</td><td>\${$inc['amount']}</td><td>{$inc['created_at']}</td></tr>";
    }
    echo "</tbody></table>";
}

// ✅ Expenses Report (Fixing the error)
if ($reportType == 'expenses') {
    echo "<h3 class='mt-4'>Expenses Report</h3>";
    $stmt = $conn->prepare("SELECT category, amount, created_at FROM expenses WHERE created_at BETWEEN ? AND ?");
    $stmt->execute([$startDate, $endDate]);
    $expenses = $stmt->fetchAll();

    echo "<table class='table table-bordered'>";
    echo "<thead><tr><th>Category</th><th>Amount</th><th>Date</th></tr></thead><tbody>";
    foreach ($expenses as $exp) {
        echo "<tr><td>{$exp['category']}</td><td>\${$exp['amount']}</td><td>{$exp['created_at']}</td></tr>";
    }
    echo "</tbody></table>";
} 

// Budget Overview
if ($reportType == 'budget') {
    echo "<h3 class='mt-4'>Budget Overview</h3>";
    $stmt = $conn->prepare("SELECT category, limit_amount FROM budgets");
    $stmt->execute();
    $budgets = $stmt->fetchAll();

    echo "<table class='table table-bordered'>";
    echo "<thead><tr><th>Category</th><th>Budget Limit</th></tr></thead><tbody>";
    foreach ($budgets as $budget) {
        echo "<tr><td>{$budget['category']}</td><td>\${$budget['limit_amount']}</td></tr>";
    }
    echo "</tbody></table>";
}
?>
