<?php
session_start();
require 'config/db.php';
include 'header.php';

// ✅ Ensure only admins can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// ✅ Fetch recent notifications
$stmt = $conn->prepare("SELECT * FROM notifications WHERE is_broadcast = 1 ORDER BY created_at DESC LIMIT 5");
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2 class="mb-4">Admin Notifications</h2>

    <?php if (isset($_SESSION['notify_message'])): ?>
        <div class="alert alert-info"><?= $_SESSION['notify_message']; unset($_SESSION['notify_message']); ?></div>
    <?php endif; ?>

    <!-- ✅ Form to Send Notification -->
    <form action="send_notification.php" method="POST" class="shadow p-4 bg-white rounded">
        <div class="mb-3">
            <label class="form-label">Notification Message</label>
            <textarea name="message" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Send Notification</button>
    </form>

    <!-- ✅ List of Recent Notifications -->
    <h3 class="mt-5">Recent Notifications</h3>
    <ul class="list-group">
        <?php foreach ($notifications as $note): ?>
            <li class="list-group-item">
                <?= htmlspecialchars($note['message']) ?>
                <small class="text-muted"><?= $note['created_at'] ?></small>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php include 'footer.php'; ?>
