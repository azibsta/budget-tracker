<?php
session_start();
require 'config/db.php';

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
        echo "Income added successfully! <a href='dashboard.php'>Back to Dashboard</a>";
    } else {
        echo "Error: Could not add income.";
    }
}
?>

<h2>Add Income</h2>
<form method="POST">
    Amount: <input type="number" name="amount" step="0.01" required><br>
    Source: <input type="text" name="source" required><br>
    Date: <input type="date" name="date" required><br>
    <button type="submit">Add Income</button>
</form>
