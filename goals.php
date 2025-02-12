<?php
session_start();
require 'config/db.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $goal_date = $_POST['goal_date'];
    $goal_description = $_POST['goal_description'];

    $stmt = $conn->prepare("INSERT INTO goals (user_id, goal_date, goal_description) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $goal_date, $goal_description]);

    $message = "Goal set successfully!";
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Set Financial Goals</h2>
    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="goal_date" class="form-label">Goal Date</label>
            <input type="date" class="form-control" id="goal_date" name="goal_date" required>
        </div>
        <div class="mb-3">
            <label for="goal_description" class="form-label">Goal Description</label>
            <textarea class="form-control" id="goal_description" name="goal_description" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Set Goal</button>
    </form>
</div>

<?php include 'footer.php'; ?>