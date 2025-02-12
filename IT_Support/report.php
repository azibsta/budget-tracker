<?php
session_start();
require '../config/db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $subject = $_POST['subject'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO support_tickets (user_id, subject, description, status, created_at) VALUES (?, ?, ?, 'open', NOW())");
    if ($stmt->execute([$user_id, $subject, $description])) {
        echo '<div class="alert alert-success text-center">Bug report submitted successfully!</div>';
    } else {
        echo '<div class="alert alert-danger text-center">Error: Could not submit bug report.</div>';
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Report a Bug or Error</h2>
    <form method="POST" class="shadow p-4 bg-white rounded">
        <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Submit</button>
    </form>
</div>

<?php include '../footer.php'; ?>