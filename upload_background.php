<?php
session_start();
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['background_image'])) {
    $file = $_FILES['background_image'];
    $allowedTypes = ['image/png', 'image/jpeg'];

    if (!in_array($file['type'], $allowedTypes)) {
        $_SESSION['upload_message'] = "❌ Only PNG and JPEG files are allowed.";
        header("Location: admin_settings.php");
        exit();
    }

    $uploadDir = "uploads/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_" . basename($file["name"]);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        // ✅ Store file path in database
        $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = 'background_image'");
        $stmt->execute([$targetFile]);

        $_SESSION['upload_message'] = "✅ Background image uploaded successfully!";
    } else {
        $_SESSION['upload_message'] = "❌ Error uploading file.";
    }
}

header("Location: admin_settings.php");
exit();
