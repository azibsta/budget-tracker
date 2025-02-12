<?php
session_start();
require 'config/db.php';
include 'header.php';

// ✅ Ensure only admins can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// ✅ Handle settings update (excluding file upload)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_FILES['background_media'])) {
    foreach ($_POST as $key => $value) {
        $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = ?");
        $stmt->execute([$value, $key]);
    }
    $_SESSION['message'] = "✅ Settings updated successfully!";
    header("Location: admin_settings.php");
    exit();
}

// ✅ Fetch current settings
$stmt = $conn->prepare("SELECT name, value FROM settings");
$stmt->execute();
$settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// ✅ Fetch available uploaded backgrounds
$backgroundFiles = glob("uploads/*.{png,jpg,jpeg,mp4,mov}", GLOB_BRACE);
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
            <label class="form-label">Select Background</label>
            <select name="background_image" class="form-control">
                <option value="">Default</option>
                <?php foreach ($backgroundFiles as $file): ?>
                    <option value="<?= $file ?>" <?= ($settings['background_image'] == $file) ? 'selected' : '' ?>>
                        <?= basename($file) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Save Settings</button>
    </form>

    <h3 class="mt-4">Upload New Background (Image or Video)</h3>

    <?php if (isset($_SESSION['upload_message'])): ?>
        <div class="alert alert-info"><?= $_SESSION['upload_message']; unset($_SESSION['upload_message']); ?></div>
    <?php endif; ?>

    <form action="upload_background.php" method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="mb-3">
            <label class="form-label">Upload Background (PNG, JPEG, MP4, MOV)</label>
            <input type="file" name="background_media" class="form-control" accept="image/png, image/jpeg, video/mp4, video/quicktime" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>

<?php include 'footer.php'; ?>
