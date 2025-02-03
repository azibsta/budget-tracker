<?php
$host = 'localhost';  // XAMPP runs MySQL on localhost
$dbname = 'budget-tracker'; // Database name
$username = 'root'; // Default XAMPP user
$password = ''; // No password by default

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Uncomment the line below to check connection:
    // echo "Connected successfully!";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
