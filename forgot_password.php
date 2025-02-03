<?php
session_start();
require 'config/db.php';
require 'vendor/autoload.php'; // Load PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate unique reset token
        $token = bin2hex(random_bytes(50));

        // Store token in database
        $stmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
        $stmt->execute([$token, $email]);

        // Create reset link
        $resetLink = "http://localhost/budget-tracker/reset_password.php?token=$token";

        // Configure PHPMailer with Mailtrap
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io'; // Mailtrap SMTP
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = '5d4f50a0bd55fa'; // Your Mailtrap Username
            $mail->Password = 'ffd2b5b8dfbaba'; // Your Mailtrap Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            // Email details
            $mail->setFrom('no-reply@budget-tracker.com', 'Budget Tracker');
            $mail->addAddress($email);
            $mail->Subject = "Password Reset Request";
            $mail->Body = "Hello,\n\nClick the link below to reset your password:\n\n$resetLink\n\nIf you did not request a password reset, please ignore this email.";

            $mail->send();
            echo "✅ A password reset link has been sent to your email!";
        } catch (Exception $e) {
            echo "❌ Email could not be sent. Error: " . $mail->ErrorInfo;
        }
    } else {
        echo "❌ Email not found!";
    }
}
?>

<h2>Forgot Password</h2>
<form method="POST">
    Enter Your Email: <input type="email" name="email" required><br>
    <button type="submit">Reset Password</button>
</form>
