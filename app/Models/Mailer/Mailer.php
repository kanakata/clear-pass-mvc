<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 1. Load Composer's autoloader
require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // 2. Server settings
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.example.com';                     // Set the SMTP server
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'your_email@example.com';               // SMTP username
    $mail->Password   = 'your_app_password';                    // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
    $mail->Port       = 587;                                    // TCP port to connect to

    // 3. Recipients
    $mail->setFrom('from@example.com', 'Your Name');
    $mail->addAddress('recipient@domain.com', 'Joe User');     // Add a recipient

    // 4. Content
    $mail->isHTML(true);                                        // Set email format to HTML
    $mail->Subject = 'PHPMailer Test';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
