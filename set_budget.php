<?php
session_start();
require 'config/db.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = trim($_POST['category']); // Allow typed category
    $limit_amount = $_POST['limit_amount'];

    // Check if budget exists for this category
    $stmt = $conn->prepare("SELECT * FROM budgets WHERE user_id = ? AND category = ?");
    $stmt->execute([$user_id, $category]);
    $existing_budget = $stmt->fetch();

    if ($existing_budget) {
        // Update budget
        $stmt = $conn->prepare("UPDATE budgets SET limit_amount = ? WHERE user_id = ? AND category = ?");
        $stmt->execute([$limit_amount, $user_id, $category]);
        $success = "✅ Budget updated successfully!";
    } else {
        // Insert new budget
        $stmt = $conn->prepare("INSERT INTO budgets (user_id, category, limit_amount) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $category, $limit_amount]);
        $success = "✅ Budget set successfully!";
    }
}

// Fetch unique categories from previous budgets and expenses for autocomplete
$stmt = $conn->prepare("SELECT DISTINCT category FROM (SELECT category FROM budgets WHERE user_id = ? UNION SELECT category FROM expenses WHERE user_id = ?) as categories");
$stmt->execute([$user_id, $user_id]);
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<div class="container mt-5">
    <h2 class="mb-4">Set Budget</h2>

    <?php if (isset($success)) echo "<div class='alert alert-success text-center'>$success</div>"; ?>

    <form method="POST" class="shadow p-4 bg-white rounded">
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
            <label class="form-label">Budget Limit ($)</label>
            <input type="number" name="limit_amount" step="0.01" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Set Budget</button>
    </form>
</div>

<?php include 'footer.php'; ?>
