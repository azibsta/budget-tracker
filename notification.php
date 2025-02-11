<?php
session_start();
require 'config/db.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch notifications
$stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll();

// ✅ Mark notifications as read
$conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?")->execute([$user_id]);

?>

<div class="container mt-5">
    <h2 class="mb-4">Notifications</h2>

    <?php if (count($notifications) == 0) { ?>
        <p class="text-muted">No notifications available.</p>
    <?php } else { ?>
        <ul class="list-group">
            <?php foreach ($notifications as $note) { ?>
                <li class="list-group-item"><?= htmlspecialchars($note['message']) ?> <small class="text-muted float-end"><?= $note['created_at'] ?></small></li>
            <?php } ?>
        </ul>
    <?php } ?>
</div>

<?php include 'footer.php'; ?>
