<?php include '../../header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">My Support Tickets</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $ticket) { ?>
                <tr>
                    <td><?= htmlspecialchars($ticket['id']) ?></td>
                    <td><?= htmlspecialchars($ticket['subject']) ?></td>
                    <td><?= htmlspecialchars($ticket['status']) ?></td>
                    <td><?= htmlspecialchars($ticket['created_at']) ?></td>
                    <td><a href="routes.php?ticket_id=<?= $ticket['id'] ?>" class="btn btn-info">View</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include '../../footer.php'; ?>