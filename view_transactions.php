<?php
session_start();
require 'config/db.php';
include 'header.php'; // Include Bootstrap navbar

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<div class="container mt-5">
    <h2 class="mb-4">Transaction History</h2>

    <ul class="nav nav-tabs" id="transactionTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="income-tab" data-bs-toggle="tab" data-bs-target="#income" type="button" role="tab">Income</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="expenses-tab" data-bs-toggle="tab" data-bs-target="#expenses" type="button" role="tab">Expenses</button>
        </li>
    </ul>

    <div class="tab-content mt-3" id="transactionTabsContent">
        <!-- Income Table -->
        <div class="tab-pane fade show active" id="income" role="tabpanel">
            <h3 class="mb-3">Income Transactions</h3>
            <table class="table table-striped">
                <thead class="table-success">
                    <tr>
                        <th>Amount ($)</th>
                        <th>Source</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM income WHERE user_id = ? ORDER BY date DESC");
                    $stmt->execute([$user_id]);
                    $incomes = $stmt->fetchAll();
                    foreach ($incomes as $income) {
                        echo "<tr>
                                <td><strong>$" . number_format($income['amount'], 2) . "</strong></td>
                                <td>{$income['source']}</td>
                                <td>{$income['date']}</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Expenses Table -->
        <div class="tab-pane fade" id="expenses" role="tabpanel">
            <h3 class="mb-3">Expense Transactions</h3>
            <table class="table table-striped">
                <thead class="table-danger">
                    <tr>
                        <th>Amount ($)</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM expenses WHERE user_id = ? ORDER BY date DESC");
                    $stmt->execute([$user_id]);
                    $expenses = $stmt->fetchAll();
                    foreach ($expenses as $expense) {
                        // Get budget for this category
                        $stmt = $conn->prepare("SELECT limit_amount FROM budgets WHERE user_id = ? AND category = ?");
                        $stmt->execute([$user_id, $expense['category']]);
                        $budget = $stmt->fetch();

                        $status = "<span class='badge bg-success'>Within Budget</span>";
                        if ($budget && $expense['amount'] > $budget['limit_amount']) {
                            $status = "<span class='badge bg-danger'>Over Budget</span>";
                        }

                        echo "<tr>
                                <td><strong>$" . number_format($expense['amount'], 2) . "</strong></td>
                                <td>{$expense['category']}</td>
                                <td>{$expense['date']}</td>
                                <td>{$status}</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
