<?php
session_start();
require 'config/db.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $amount = $_POST['amount'];
    $source = $_POST['source'];
    $date = $_POST['date'];

    $stmt = $conn->prepare("INSERT INTO income (user_id, amount, source, date) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$user_id, $amount, $source, $date])) {
        echo '<div class="alert alert-success text-center">Income added successfully!</div>';
    } else {
        echo '<div class="alert alert-danger text-center">Error: Could not add income.</div>';
    }
}
?>

<div class="container mt-5">
    <h2>Add Income</h2>
    <form method="POST" class="shadow p-4 bg-white rounded">
        <div class="mb-3">
            <label class="form-label">Amount</label>
            <input type="number" name="amount" step="0.01" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Source</label>
            <input type="text" name="source" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Income</button>
    </form>
</div>

<?php include 'footer.php'; ?>
