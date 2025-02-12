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
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    $attachmentPath = NULL;

    // ✅ Handle file upload
    if (!empty($_FILES['attachment']['name'])) {
        $targetDir = "uploads/"; // Ensure this folder exists in your project
        $fileName = time() . "_" . basename($_FILES['attachment']['name']);
        $targetFile = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // ✅ Allow only PNG, JPG, JPEG
        $allowedTypes = ['jpg', 'jpeg', 'png'];
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetFile)) {
                $attachmentPath = $targetFile;
            } else {
                $error = "❌ Error uploading the file.";
            }
        } else {
            $error = "❌ Only JPG, JPEG, and PNG files are allowed.";
        }
    }

    if (!empty($subject) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO it_support_tickets (user_id, subject, message, attachment) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $subject, $message, $attachmentPath]);
        $success = "✅ Issue reported successfully!";
    } else {
        $error = "❌ All fields are required!";
    }
}


?>

<div class="container mt-5">
    <h2 class="mb-4">Report an IT Issue</h2>

    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST" enctype="multipart/form-data" class="shadow p-4 bg-white rounded">
        <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Describe the issue</label>
            <textarea name="message" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Attach Screenshot (JPG, PNG)</label>
            <input type="file" name="attachment" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary w-100">Submit Issue</button>
    </form>
</div>

<?php include 'footer.php'; ?>
