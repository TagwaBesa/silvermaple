<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

// Use Gmail with SMTP
$mail->isSMTP();

// Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';

// Set the SMTP port number - 587 for TLS, 465 for SSL
$mail->Port = 465;

// Enable TLS encryptionpp
$mail->SMTPSecure = 'ssl';

// Enable SMTP authentication
$mail->SMTPAuth = true;

// Your Gmail username (your full Gmail email address)
$mail->Username = 'besaemmanuel99@gmail.com';

// Your Gmail password or App Password
$mail->Password = 'uzlj bakf ufhd nanx';

// Set the 'From' email address
$mail->setFrom('besaemmanuel99@gmail.com', 'Besa');

// Add a recipient
$mail->addAddress('1804685@northrise.net', 'Emmanuel');

// Email subject
$mail->Subject = 'Subject of your email';

// Email body
$mail->Body = 'Your email message here.';

// Send the email
if ($mail->send()) {
    echo 'Email sent successfully.';
} else {
    echo 'Email could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}
?>