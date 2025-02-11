<?php
session_start();
require 'config/db.php';
include 'header.php';

// ✅ Ensure only admins can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// ✅ Handle settings update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $key => $value) {
        $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = ?");
        $stmt->execute([$value, $key]);
    }
    $_SESSION['message'] = "✅ Settings updated successfully!";
    header("Location: admin_settings.php");
    exit();
}

// ✅ Fetch current settings
$stmt = $conn->prepare("SELECT name, value FROM settings"); // Select only two columns
$stmt->execute();
$settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<div class="container mt-5">
    <h2 class="mb-4">Manage Application Settings</h2>

    <?php if (isset($_SESSION['message'])) { ?>
        <div class="alert alert-success text-center"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php } ?>

    <form method="POST" class="shadow p-4 bg-white rounded">
        <div class="mb-3">
            <label class="form-label">Theme</label>
            <select name="theme" class="form-control">
                <option value="light" <?= ($settings['theme'] == 'light') ? 'selected' : '' ?>>Light Mode</option>
                <option value="dark" <?= ($settings['theme'] == 'dark') ? 'selected' : '' ?>>Dark Mode</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Enable Notifications</label>
            <select name="notifications_enabled" class="form-control">
                <option value="1" <?= ($settings['notifications_enabled'] == '1') ? 'selected' : '' ?>>Enabled</option>
                <option value="0" <?= ($settings['notifications_enabled'] == '0') ? 'selected' : '' ?>>Disabled</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Enable Feature X</label>
            <select name="feature_x_enabled" class="form-control">
                <option value="1" <?= ($settings['feature_x_enabled'] == '1') ? 'selected' : '' ?>>Enabled</option>
                <option value="0" <?= ($settings['feature_x_enabled'] == '0') ? 'selected' : '' ?>>Disabled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Save Settings</button>
    </form>
</div>

<?php include 'footer.php'; ?>
