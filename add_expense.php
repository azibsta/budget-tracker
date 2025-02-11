<?php
session_start();
require 'config/db.php';
require 'config/alerts.php'; // Import alert system
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];
    $category = trim($_POST['category']); // Allow typed category
    $date = $_POST['date'];

    // ✅ Insert expense into database
    $stmt = $conn->prepare("INSERT INTO expenses (user_id, amount, category, date) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$user_id, $amount, $category, $date])) {
        $success = "✅ Expense added successfully!";
        
        // ✅ Run alert checks (Check if budget is exceeded)
        runAlerts($user_id);
    } else {
        $error = "❌ Error: Could not add expense.";
    }
}

// ✅ Fetch unique categories from previous expenses for autocomplete
$stmt = $conn->prepare("SELECT DISTINCT category FROM expenses WHERE user_id = ?");
$stmt->execute([$user_id]);
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<div class="container mt-5">
    <h2 class="mb-4">Add Expense</h2>

    <?php if (isset($success)) echo "<div class='alert alert-success text-center'>$success</div>"; ?>
    <?php if (isset($error)) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>

    <form method="POST" class="shadow p-4 bg-white rounded">
        <div class="mb-3">
            <label class="form-label">Amount ($)</label>
            <input type="number" name="amount" step="0.01" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="category" id="categoryInput" class="form-control" required list="categoryList">
            <datalist id="categoryList">
                <?php foreach ($categories as $cat) { ?>
                    <option value="<?= htmlspecialchars($cat) ?>">
                <?php } ?>
            </datalist>
        </div>
        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Add Expense</button>
    </form>
</div>

<?php include 'footer.php'; ?>
