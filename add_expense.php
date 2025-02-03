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
    $category = $_POST['category'];
    $date = $_POST['date'];

    $stmt = $conn->prepare("INSERT INTO expenses (user_id, amount, category, date) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$user_id, $amount, $category, $date])) {
        echo "Expense added successfully! <a href='dashboard.php'>Back to Dashboard</a>";
    } else {
        echo "Error: Could not add expense.";
    }
}
?>

<h2>Add Expense</h2>
<form method="POST">
    Amount: <input type="number" name="amount" step="0.01" required><br>
    Category: <input type="text" name="category" required><br>
    Date: <input type="date" name="date" required><br>
    <button type="submit">Add Expense</button>
</form>
