<?php
session_start();
require 'config/db.php';
include 'header.php';

// ✅ Ensure only IT Support can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'it_support' || !isset($_GET['id'])) {
    header("Location: it_support_dashboard.php");
    exit();
}

$ticket_id = $_GET['id'];

// ✅ Fetch ticket details
$stmt = $conn->prepare("SELECT t.*, u.name FROM it_support_tickets t JOIN users u ON t.user_id = u.id WHERE t.id = ?");
$stmt->execute([$ticket_id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    die("❌ Ticket not found!");
}

// ✅ Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'])) {
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE it_support_tickets SET status = ? WHERE id = ?");
    $stmt->execute([$status, $ticket_id]);
    header("Location: view_ticket.php?id=$ticket_id");
    exit();
}
?>

<div class="container mt-5">
    <h2 class="mb-4"><?= htmlspecialchars($ticket['subject']) ?></h2>
    <p><strong>Reported by:</strong> <?= htmlspecialchars($ticket['name']) ?></p>
    <p><strong>Message:</strong> <?= nl2br(htmlspecialchars($ticket['message'])) ?></p>
    <p><strong>Status:</strong> <span class="badge bg-primary"><?= ucfirst($ticket['status']) ?></span></p>

    <form method="POST" class="mt-4">
        <label>Update Status:</label>
        <select name="status" class="form-control">
            <option value="open" <?= $ticket['status'] == 'open' ? 'selected' : '' ?>>Open</option>
            <option value="in_progress" <?= $ticket['status'] == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
            <option value="resolved" <?= $ticket['status'] == 'resolved' ? 'selected' : '' ?>>Resolved</option>
        </select>
        <button type="submit" class="btn btn-primary mt-2">Update</button>
    </form>
</div>

<?php include 'footer.php'; ?>
