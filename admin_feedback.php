<?php
session_start();
require 'config/db.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT feedbacks.*, users.name FROM feedbacks JOIN users ON feedbacks.user_id = users.id ORDER BY feedbacks.created_at DESC");
$stmt->execute();
$feedbacks = $stmt->fetchAll();
?>

<div class="container mt-5">
    <h2 class="mb-4">User Feedback</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>User</th>
                <th>Feedback</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($feedbacks as $feedback) { ?>
                <tr>
                    <td><?= $feedback['name'] ?></td>
                    <td><?= $feedback['message'] ?></td>
                    <td><?= $feedback['created_at'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
