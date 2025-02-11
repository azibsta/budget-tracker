<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'config/db.php';

// Fetch theme setting
$stmt = $conn->prepare("SELECT value FROM settings WHERE name = 'theme'");
$stmt->execute();
$theme = $stmt->fetchColumn();

// Fetch unread notifications count (if user is logged in)
$unreadNotifications = 0;
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->execute([$_SESSION['user_id']]);
    $unreadNotifications = $stmt->fetchColumn();
}

// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);

// Pages where the navbar should be hidden
$hideNavbarPages = ['login.php', 'register.php', 'forgot_password.php', 'reset_password.php'];
?>

<?php
require 'config/db.php';

function getBackgroundImage() {
    global $conn;
    $stmt = $conn->prepare("SELECT value FROM settings WHERE name = 'background_image'");
    $stmt->execute();
    $backgroundImage = $stmt->fetchColumn();

    return !empty($backgroundImage) ? $backgroundImage : "default-background.jpg";
}
?>
<style>
body {
    background: url('<?= getBackgroundImage() ?>') no-repeat center center fixed;
    background-size: cover;
}
</style>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Budget Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Apply Theme -->
    <?php if ($theme == 'dark') { ?>
        <link rel="stylesheet" href="dark-theme.css">
    <?php } else { ?>
        <link rel="stylesheet" href="light-theme.css">
    <?php } ?>
</head>
<body class="<?= $theme == 'dark' ? 'bg-dark text-white' : 'bg-light' ?>">

<!-- ✅ Hide Navbar on Login, Register, Forgot Password, and Reset Password -->
<?php if (!in_array($current_page, $hideNavbarPages)) { ?>
    <nav class="navbar navbar-expand-lg <?= $theme == 'dark' ? 'navbar-dark bg-dark' : 'navbar-light bg-white' ?>">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Budget Tracker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="add_income.php">Add Income</a></li>
                    <li class="nav-item"><a class="nav-link" href="add_expense.php">Add Expense</a></li>
                    <li class="nav-item"><a class="nav-link" href="set_budget.php">Set Budget</a></li>
                    <li class="nav-item"><a class="nav-link" href="view_transactions.php">Transactions</a></li>
                    <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>

                    <!-- Notifications Icon -->
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="notification.php">
                            🔔 Notifications
                            <?php if ($unreadNotifications > 0) { ?>
                                <span class="badge bg-danger"><?= $unreadNotifications ?></span>
                            <?php } ?>
                        </a>
                    </li>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
                        <li class="nav-item"><a class="nav-link text-warning" href="admin_dashboard.php">Admin Panel</a></li>
                    <?php } ?>

                    <li class="nav-item"><a class="nav-link btn btn-danger text-black" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
<?php } ?>
