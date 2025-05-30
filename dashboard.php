<?php
session_start();
require 'config/db.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch notifications (User-Specific + Broadcasts)
$stmt = $conn->prepare("
    SELECT * FROM notifications 
    WHERE user_id = ? OR is_broadcast = 1 
    ORDER BY created_at DESC LIMIT 5
");
$stmt->execute([$_SESSION['user_id']]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total income
$stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS total_income FROM income WHERE user_id = ?");
$stmt->execute([$user_id]);
$income = $stmt->fetch()['total_income'] ?? 0.00;

// Get total expenses
$stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS total_expenses FROM expenses WHERE user_id = ?");
$stmt->execute([$user_id]);
$expenses = $stmt->fetch()['total_expenses'] ?? 0.00;

// Calculate balance
$balance = $income - $expenses;

// Fetch budgets & spent per category
$stmt = $conn->prepare("
    SELECT category, limit_amount 
    FROM budgets 
    WHERE user_id = ? 
    AND category IN (SELECT DISTINCT category FROM expenses WHERE user_id = ?)
");
$stmt->execute([$user_id, $user_id]);
$budgets = $stmt->fetchAll(PDO::FETCH_ASSOC);

$budgetData = [];
$alerts = [];

foreach ($budgets as $budget) {
    $category = $budget['category'];
    $limit_amount = $budget['limit_amount'];

    // Ensure category exists in expenses before adding
    $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS total_spent FROM expenses WHERE user_id = ? AND category = ?");
    $stmt->execute([$user_id, $category]);
    $total_spent = $stmt->fetch()['total_spent'] ?? 0.00;

    if ($total_spent > 0) { // Only add if there are expenses in this category
        $budgetData[] = [
            'category' => $category,
            'limit' => $limit_amount,
            'spent' => $total_spent
        ];
    }

    // Create alert if spent exceeds budget
    if ($total_spent > $limit_amount) {
        $alerts[] = "<div class='alert alert-danger text-center'><strong>Warning!</strong> You have exceeded your budget for <b>{$category}</b>. Budget: \${$limit_amount}, Spent: \${$total_spent}.</div>";
    }
}

// Fetch unread notifications
$stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll();

// Mark notifications as read
$conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?")->execute([$user_id]);

?>

<div class="container mt-5">
    <h2 class="mb-4">Dashboard</h2>

    
    <!-- Show Budget Alerts -->
    <?php foreach ($alerts as $alert) {
        echo $alert;
    } ?>

    <h3 class="mt-4">Notifications</h3>
    <ul class="list-group">
        <?php foreach ($notifications as $note): ?>
            <li class="list-group-item">
                <?= htmlspecialchars($note['message']) ?> 
                <small class="text-muted"><?= $note['created_at'] ?></small>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="row">
        <div class="col-md-4">
            <div class="card bg-success text-white text-center p-3">
                <h4>Total Income</h4>
                <h3>$<?= number_format($income, 2) ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white text-center p-3">
                <h4>Total Expenses</h4>
                <h3>$<?= number_format($expenses, 2) ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white text-center p-3">
                <h4>Current Balance</h4>
                <h3>$<?= number_format($balance, 2) ?></h3>
            </div>
        </div>
    </div>

    <h3 class="mt-5">Budget Overview</h3>
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Category</th>
                <th>Budget Limit ($)</th>
                <th>Spent ($)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($budgetData as $data) { 
                $status = $data['spent'] > $data['limit'] 
                    ? "<span class='badge bg-danger'>Over Budget</span>" 
                    : "<span class='badge bg-success'>Within Budget</span>";
            ?>
                <tr>
                    <td><?= ucfirst($data['category']) ?></td>
                    <td>$<?= number_format($data['limit'], 2) ?></td>
                    <td>$<?= number_format($data['spent'], 2) ?></td>
                    <td><?= $status ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="row mt-5">
        <div class="col-md-6 text-center">
            <h3>Income vs Expenses</h3>
            <div class="chart-container">
                <canvas id="incomeExpenseChart"></canvas>
            </div>
        </div>
        <div class="col-md-6 text-center">
            <h3>Expenses by Category</h3>
            <div class="chart-container">
                <canvas id="expenseCategoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Financial Advisor Section -->
    <h3 class="mt-5">Financial Advisor</h3>
    <div class="list-group">
        <a href="analyze.php" class="list-group-item list-group-item-action">Analyze Financial Data</a>
        <a href="goals.php" class="list-group-item list-group-item-action">Set Financial Goals</a>
    </div>
</div>

<style>
.chart-container {
    width: 100%;
    max-width: 400px; /* Limits width */
    height: 300px !important; /* Fixes height */
    margin: auto; /* Centers charts */
}
</style>

<script>
    var ctx1 = document.getElementById('incomeExpenseChart').getContext('2d');
    var incomeExpenseChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Income', 'Expenses'],
            datasets: [{
                label: 'Amount ($)',
                data: [<?= $income ?>, <?= $expenses ?>],
                backgroundColor: ['#28a745', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    var ctx2 = document.getElementById('expenseCategoryChart').getContext('2d');
    var expenseCategoryChart = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_column($budgetData, 'category')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($budgetData, 'spent')) ?>,
                backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>

<?php include 'footer.php'; ?>
