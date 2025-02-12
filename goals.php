<?php
session_start();
require 'config/db.php';
include 'header.php'; // Adjusted path

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $goal_date = $_POST['goal_date'];
    $goal_description = $_POST['goal_description'];

    $stmt = $conn->prepare("INSERT INTO goals (user_id, goal_date, goal_description) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $goal_date, $goal_description]);

    $message = "Goal set successfully!";
}

// Fetch all goals for the user
$stmt = $conn->prepare("SELECT * FROM goals WHERE user_id = ? ORDER BY goal_date ASC");
$stmt->execute([$user_id]);
$goals = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    <h3 class="mt-5">Your Financial Goals</h3>
    <ul class="list-group">
        <?php foreach ($goals as $goal): ?>
            <li class="list-group-item">
                <strong><?= htmlspecialchars($goal['goal_date'], ENT_QUOTES, 'UTF-8') ?>:</strong>
                <?= htmlspecialchars($goal['goal_description'], ENT_QUOTES, 'UTF-8') ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="ask-advisor-dashboard.php" class="btn btn-secondary mt-4">Ask Advisor</a>
</div>

<?php include 'footer.php'; // Adjusted path ?>