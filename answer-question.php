<?php
session_start();
require 'config/db.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the user is an advisor
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user['role'] !== 'advisor') {
    header("Location: login.php");
    exit();
}

// Fetch all questions without answers
$stmt = $conn->prepare("
    SELECT q.id AS question_id, q.question, q.created_at AS question_date, q.user_id
    FROM advisor_questions q
    LEFT JOIN advisor_answers a ON q.id = a.question_id
    JOIN users u ON q.user_id = u.id
    WHERE a.answer IS NULL
    ORDER BY q.created_at DESC
");
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id']) && isset($_POST['answer'])) {
    $question_id = $_POST['question_id'];
    $answer = $_POST['answer'];

    $stmt = $conn->prepare("INSERT INTO advisor_answers (question_id, answer) VALUES (?, ?)");
    $stmt->execute([$question_id, $answer]);

    header("Location: answer-question.php");
    exit();
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Advisor Dashboard</h2>
    <h3 class="mt-5">Questions to Answer</h3>
    <ul class="list-group">
        <?php foreach ($questions as $question): ?>
            <li class="list-group-item">
                <strong>Question:</strong> <?= htmlspecialchars($question['question'], ENT_QUOTES, 'UTF-8') ?>
                <br>
                <small class="text-muted">Asked by <?= htmlspecialchars($question['user_id'], ENT_QUOTES, 'UTF-8') ?> (User ID: <?= htmlspecialchars($question['user_id'], ENT_QUOTES, 'UTF-8') ?>) on <?= htmlspecialchars($question['question_date'], ENT_QUOTES, 'UTF-8') ?></small>
                <form method="post" action="answer-question.php" class="mt-2">
                    <input type="hidden" name="question_id" value="<?= $question['question_id'] ?>">
                    <div class="mb-3">
                        <label for="answer" class="form-label">Answer</label>
                        <textarea class="form-control" id="answer" name="answer" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Submit Answer</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php include 'footer.php'; ?>

