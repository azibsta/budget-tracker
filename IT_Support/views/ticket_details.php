<?php include '../../header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Ticket Details</h2>
    <div class="card">
        <div class="card-header">
            <h3><?= htmlspecialchars($ticket['subject']) ?></h3>
        </div>
        <div class="card-body">
            <p><strong>Status:</strong> <?= htmlspecialchars($ticket['status']) ?></p>
            <p><strong>Description:</strong></p>
            <p><?= nl2br(htmlspecialchars($ticket['description'])) ?></p>
            <p><strong>Created At:</strong> <?= htmlspecialchars($ticket['created_at']) ?></p>
        </div>
    </div>

    <div class="mt-4">
        <h4>Update Status</h4>
        <form method="POST" action="../routes.php">
            <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($ticket['id']) ?>">
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="open" <?= $ticket['status'] == 'open' ? 'selected' : '' ?>>Open</option>
                    <option value="in_progress" <?= $ticket['status'] == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="closed" <?= $ticket['status'] == 'closed' ? 'selected' : '' ?>>Closed</option>
                </select>
            </div>
            <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
        </form>
    </div>

    <div class="mt-4">
        <h4>Add Comment</h4>
        <form method="POST" action="../routes.php">
            <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($ticket['id']) ?>">
            <div class="mb-3">
                <label class="form-label">Comment</label>
                <textarea name="comment" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" name="add_comment" class="btn btn-primary">Add Comment</button>
        </form>
    </div>

    <div class="mt-4">
        <h4>Comments</h4>
        <?php foreach ($comments as $comment) { ?>
            <div class="card mb-2">
                <div class="card-body">
                    <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                    <p class="text-muted"><small>Posted on <?= htmlspecialchars($comment['created_at']) ?></small></p>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php include '../../footer.php'; ?>