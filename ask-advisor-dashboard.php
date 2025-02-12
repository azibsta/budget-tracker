<?php
session_start();
require 'config/db.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question'])) {
    $question = $_POST['question'];

    $stmt = $conn->prepare("INSERT INTO advisor_questions (user_id, question) VALUES (?, ?)");
    $stmt->execute([$user_id, $question]);

    $message = "Question submitted successfully!";
}

// Fetch all questions and their answers
$stmt = $conn->prepare("
    SELECT q.id AS question_id, q.question, a.answer, a.created_at AS answer_date
    FROM advisor_questions q
    LEFT JOIN advisor_answers a ON q.id = a.question_id
    WHERE q.user_id = ?
    ORDER BY q.created_at DESC
");
$stmt->execute([$user_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2 class="mb-4">Financial Advisor Dashboard</h2>
    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="question" class="form-label">Ask a Question</label>
            <textarea class="form-control" id="question" name="question" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Question</button>
    </form>

    <h3 class="mt-5">Your Questions and Answers</h3>
    <ul class="list-group">
        <?php foreach ($questions as $question): ?>
            <li class="list-group-item">
                <strong>Question:</strong> <?= htmlspecialchars($question['question'], ENT_QUOTES, 'UTF-8') ?>
                <?php if ($question['answer']): ?>
                    <div class="mt-2">
                        <strong>Answer:</strong> <?= htmlspecialchars($question['answer'], ENT_QUOTES, 'UTF-8') ?>
                        <small class="text-muted">(Answered on <?= htmlspecialchars($question['answer_date'], ENT_QUOTES, 'UTF-8') ?>)</small>
                    </div>
                <?php else: ?>
                    <div class="mt-2 text-muted">No answer yet.</div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php include 'footer.php'; ?>