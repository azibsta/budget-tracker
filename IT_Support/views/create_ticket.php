<?php include '../../header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Create Support Ticket</h2>
    <form method="POST" action="../routes.php">
        <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" name="create_ticket" class="btn btn-primary w-100">Submit Ticket</button>
    </form>
</div>

<?php include '../../footer.php'; ?>