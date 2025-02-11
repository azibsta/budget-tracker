<?php
session_start();
require 'config/db.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Default values
$reportType = $_GET['report_type'] ?? 'user_activity';
$startDate = $_GET['start_date'] ?? date('Y-m-01');
$endDate = $_GET['end_date'] ?? date('Y-m-t');

?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Admin Reports</h2>

    <!-- Report Selection Form -->
    <form method="GET" action="admin_reports.php" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="report_type" class="form-label">Select Report Type:</label>
                <select name="report_type" id="report_type" class="form-select">
                    <option value="user_activity" <?= $reportType == 'user_activity' ? 'selected' : '' ?>>User Activity</option>
                    <option value="income" <?= $reportType == 'income' ? 'selected' : '' ?>>Income Report</option>
                    <option value="expenses" <?= $reportType == 'expenses' ? 'selected' : '' ?>>Expenses Report</option>
                    <option value="budget" <?= $reportType == 'budget' ? 'selected' : '' ?>>Budget Overview</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="start_date" class="form-label">Start Date:</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="<?= $startDate ?>">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">End Date:</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="<?= $endDate ?>">
            </div>
            <div class="col-md-2 d-grid">
                <label class="form-label d-block">&nbsp;</label>
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </div>
        </div>
    </form>

    <!-- Display Report -->
    <?php include 'generate_report.php'; ?>

</div>

<?php include 'footer.php'; ?>
