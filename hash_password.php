<?php
$password = "admin123"; // Change this to the correct password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
echo "Hashed Password: " . $hashedPassword;
?>
