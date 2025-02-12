<?php
session_start();
require 'C:/xampp/htdocs/budget-tracker/config/db.php';

include 'C:/xampp/htdocs/budget-tracker/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO support_tickets (user_id, subject, description, status, created_at) VALUES (?, ?, ?, 'open', NOW())");
    if ($stmt->execute([$user_id, $subject, $message])) {
        echo '<div class="alert alert-success text-center">Support request submitted successfully!</div>';
    } else {
        echo '<div class="alert alert-danger text-center">Error: Could not submit support request.</div>';
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">IT Support</h2>
    <form method="POST" class="shadow p-4 bg-white rounded">
        <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Submit</button>
    </form>
</div>

<?php include 'C:/xampp/htdocs/budget-tracker/footer.php'; ?>