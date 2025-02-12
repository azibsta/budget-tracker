<?php
session_start();
require 'config/db.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// ✅ Fetch total users
$stmt = $conn->prepare("SELECT COUNT(*) AS total_users FROM users");
$stmt->execute();
$total_users = $stmt->fetch()['total_users'];
?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Admin Dashboard</h2>

    <div class="row text-center">
        <div class="col-mt-5">
            <div class="card bg-primary text-white p-4">
                <h4>Total Users</h4>
                <h3><?= $total_users ?></h3>
            </div>
        </div>
    </div>

    <!-- ✅ Generate Reports Section -->
    <div class="row mt-4">
        <div class="col-mt-5">
            <div class="card bg-info text-white text-center p-3">
                <h4>View Reports</h4>
                <p>Generate reports on system usage and financial trends.</p>
                <a href="admin_reports.php" class="btn btn-light">Generate Reports</a>
            </div>
        </div>
    </div>

    <div class="row mt-4">
    <div class="col-mt-5">
        <div class="card bg-info text-white text-center p-3">
            <h4>Manage Settings</h4>
            <p>Update system configurations and features.</p>
            <a href="admin_settings.php" class="btn btn-light">Go to Settings</a>
        </div>
    </div>
    <div class="row mt-4">
    <div class="col-mt-5">
        <div class="card bg-info text-white text-center p-3">
            <h4>Manage Notifications</h4>
            <p>Send notifications to all users.</p>
            <a href="admin_notifications.php" class="btn btn-light">Go to Notifications</a>
        </div>
    </div>
    </div>
</div>



    <!-- ✅ Manage Users -->
    <h3 class="mt-5">Manage Users</h3>
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->prepare("SELECT id, name, email, role FROM users");
            $stmt->execute();
            $users = $stmt->fetchAll();

            foreach ($users as $user) { ?>
                <tr>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <form method="POST" action="update_role.php">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <select name="role" class="form-select" onchange="this.form.submit()">
                                <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="advisor" <?= $user['role'] == 'advisor' ? 'selected' : '' ?>>Advisor</option>
                                <option value="it-support" <?= $user['role'] == 'it_suppory' ? 'selected' : '' ?>>IT Support</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="delete_user.php" onsubmit="return confirm('Are you sure?')">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- ✅ View & Manage User Feedback -->
    <h3 class="mt-5">User Feedback</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>User</th>
                <th>Feedback</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->prepare("SELECT feedbacks.id, users.name, feedbacks.message FROM feedbacks JOIN users ON feedbacks.user_id = users.id");
            $stmt->execute();
            $feedbacks = $stmt->fetchAll();

            foreach ($feedbacks as $feedback) { ?>
                <tr>
                    <td><?= htmlspecialchars($feedback['name']) ?></td>
                    <td><?= htmlspecialchars($feedback['message']) ?></td>
                    <td>
                        <form method="POST" action="delete_feedback.php">
                            <input type="hidden" name="feedback_id" value="<?= $feedback['id'] ?>">
                            <button type="submit" class="btn btn-warning btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
