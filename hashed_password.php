<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = trim($_POST["password"]);
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    } else {
        $error = "❌ Please enter a password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Hashed Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Generate a Hashed Password</h2>

        <form method="POST" class="shadow p-4 bg-white rounded">
            <div class="mb-3">
                <label class="form-label">Enter Password</label>
                <input type="text" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Generate Hash</button>
        </form>

        <?php if (isset($hashedPassword)): ?>
            <div class="alert alert-success mt-4">
                <strong>Hashed Password:</strong> 
                <input type="text" class="form-control" value="<?= htmlspecialchars($hashedPassword) ?>" readonly>
            </div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger mt-4"><?= $error ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
