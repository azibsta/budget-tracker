<?php
require 'vendor/autoload.php'; // Ensure PHPMailer is installed

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'sandbox.smtp.mailtrap.io'; // Mailtrap SMTP Server
    $mail->SMTPAuth = true;
    $mail->Port = 2525;
    $mail->Username = '5d4f50a0bd55fa'; // Your Mailtrap Username
    $mail->Password = 'ffd2b5b8dfbaba'; // Your Mailtrap Password

    // Email Details
    $mail->setFrom('no-reply@budget-tracker.com', 'Budget Tracker');
    $mail->addAddress('test@example.com'); // Change to a valid email for testing

    $mail->Subject = "Test Email from Mailtrap";
    $mail->Body = "This is a test email sent using Mailtrap SMTP.";

    $mail->send();
    echo "✅ Email sent successfully!";
} catch (Exception $e) {
    echo "❌ Email sending failed. Error: " . $mail->ErrorInfo;
}
?>
