<?php
session_start();
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['background_media'])) {
    $file = $_FILES['background_media'];
    $allowedTypes = ['image/png', 'image/jpeg', 'video/mp4', 'video/quicktime'];

    if (!in_array($file['type'], $allowedTypes)) {
        $_SESSION['upload_message'] = "❌ Only PNG, JPEG, MP4, and MOV files are allowed.";
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
        $_SESSION['upload_message'] = "✅ File uploaded successfully!";
    } else {
        $_SESSION['upload_message'] = "❌ Error uploading file.";
    }
}

header("Location: admin_settings.php");
exit();
