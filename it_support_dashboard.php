<?php
session_start();
require 'config/db.php';
include 'header.php';

// ✅ Ensure only IT Support can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'it_support') {
    header("Location: login.php");
    exit();
}

// ✅ Handle status updates if submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ticket_id'], $_POST['status'])) {
    $ticket_id = $_POST['ticket_id'];
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE it_support_tickets SET status = ? WHERE id = ?");
    $stmt->execute([$status, $ticket_id]);
}

// ✅ Fetch all IT support tickets
$stmt = $conn->prepare("
    SELECT t.*, u.name 
    FROM it_support_tickets t 
    JOIN users u ON t.user_id = u.id 
    ORDER BY t.created_at DESC
");
$stmt->execute();
$tickets = $stmt->fetchAll();
?>

<div class="container mt-5">
    <h2 class="mb-4">IT Support Dashboard</h2>

    <?php if (count($tickets) == 0) { ?>
        <p class="text-muted">No issues reported.</p>
    <?php } else { ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?= htmlspecialchars($ticket['name']) ?></td>
                        <td><?= htmlspecialchars($ticket['subject']) ?></td>
                        <td>
                            <span class="badge bg-<?= $ticket['status'] === 'resolved' ? 'success' : ($ticket['status'] === 'in_progress' ? 'warning' : 'danger') ?>">
                                <?= ucfirst($ticket['status']) ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#ticketModal<?= $ticket['id'] ?>">View</button>
                        </td>
                    </tr>

                    <!-- ✅ Modal for Viewing & Updating Ticket -->
                    <div class="modal fade" id="ticketModal<?= $ticket['id'] ?>" tabindex="-1" aria-labelledby="ticketLabel<?= $ticket['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="ticketLabel<?= $ticket['id'] ?>"><?= htmlspecialchars($ticket['subject']) ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Reported by:</strong> <?= htmlspecialchars($ticket['name']) ?></p>
                                    <p><strong>Message:</strong> <?= nl2br(htmlspecialchars($ticket['message'])) ?></p>
                                    <p><strong>Status:</strong> 
                                        <span class="badge bg-primary"><?= ucfirst($ticket['status']) ?></span>
                                    </p>

                                    <!-- ✅ Display attachment if available -->
                                    <?php if (!empty($ticket['attachment'])): ?>
                                        <p><strong>Attachment:</strong></p>
                                        <img src="<?= htmlspecialchars($ticket['attachment']) ?>" class="img-fluid rounded mb-3" alt="User Attachment">
                                    <?php endif; ?>

                                    <form method="POST">
                                        <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                                        <label>Update Status:</label>
                                        <select name="status" class="form-control">
                                            <option value="open" <?= $ticket['status'] == 'open' ? 'selected' : '' ?>>Open</option>
                                            <option value="in_progress" <?= $ticket['status'] == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                            <option value="resolved" <?= $ticket['status'] == 'resolved' ? 'selected' : '' ?>>Resolved</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary mt-2">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </tbody>
        </table>
    <?php } ?>
</div>

<?php include 'footer.php'; ?>
