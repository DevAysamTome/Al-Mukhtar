<?php
// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'C:/composer/vendor/autoload.php';
require 'C:/composer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'C:/composer/vendor/phpmailer/phpmailer/src/SMTP.php';
require 'C:/composer/vendor/phpmailer/phpmailer/src/Exception.php';

// Create a new PHPMailer instance
$mail = new PHPMailer;

// SMTP configuration
$mail->IsSMTP();     
$mail->Host = 'smtp.gmail.com';        
$mail->SMTPAuth = true;
$mail->Username = "aysamtohme@gmail.com";
$mail->Password = "aysam123456789";
$mail->Port = 587;
$mail->SMTPSecure = 'tls'; 
$mail->SMTPDebug = SMTP::DEBUG_CONNECTION;

// Sender and recipient information
$recipient_email = $_POST['email'];
$name = $_POST['name'];
$mail->setFrom('aysamtohme@gmail.com', 'Aysam Tohme',0);
$mail->addAddress($recipient_email);

// Email subject and body
$mail->Subject =$_POST['subject'];
$mail->Body = $_POST['message'];

// Send the email
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message sent!';
}
?>