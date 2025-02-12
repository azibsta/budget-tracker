<?php
session_start();
require 'config/db.php';
include 'header.php'; // Adjusted path

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $monthly_income = $_POST['monthly_income'];
    $monthly_expenses = $_POST['monthly_expenses'];

    $advice = [];

    if ($monthly_expenses > $monthly_income) {
        $advice[] = "Your expenses exceed your income. Consider reducing unnecessary expenses.";
    } else {
        $advice[] = "Your income exceeds your expenses. Consider saving or investing the surplus.";
    }

    if ($monthly_expenses > 0.5 * $monthly_income) {
        $advice[] = "Your expenses are more than 50% of your income. Try to save at least 20% of your income.";
    } else {
        $advice[] = "Good job! Your expenses are less than 50% of your income.";
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Analyze Financial Data</h2>
    <form method="post">
        <div class="mb-3">
            <label for="monthly_income" class="form-label">Monthly Income ($)</label>
            <input type="number" class="form-control" id="monthly_income" name="monthly_income" required>
        </div>
        <div class="mb-3">
            <label for="monthly_expenses" class="form-label">Monthly Expenses ($)</label>
            <input type="number" class="form-control" id="monthly_expenses" name="monthly_expenses" required>
        </div>
        <button type="submit" class="btn btn-primary">Analyze</button>
    </form>

    <?php if (isset($advice)): ?>
        <h3 class="mt-4">Financial Advice</h3>
        <ul>
            <?php foreach ($advice as $tip): ?>
                <li><?= htmlspecialchars($tip, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?php include 'footer.php'; // Adjusted path ?>