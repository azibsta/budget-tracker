<?php
session_start();
require 'config/db.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];

    // Insert feedback into database
    $stmt = $conn->prepare("INSERT INTO feedbacks (user_id, message) VALUES (?, ?)");
    if ($stmt->execute([$user_id, $message])) {
        echo '<div class="alert alert-success text-center">✅ Feedback submitted successfully!</div>';
    } else {
        echo '<div class="alert alert-danger text-center">❌ Error: Could not submit feedback.</div>';
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Submit Feedback</h2>
    <form method="POST" class="shadow p-4 bg-white rounded">
        <div class="mb-3">
            <label class="form-label">Your Feedback</label>
            <textarea name="message" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Submit</button>
    </form>
</div>

<?php include 'footer.php'; ?>
