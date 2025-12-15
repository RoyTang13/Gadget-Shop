<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../lib/Exception.php';
require '../lib/PHPMailer.php';
require '../lib/SMTP.php';

$mail = new PHPMailer(true);

try {
    // SMTP setup
    $mail->isSMTP();
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];
    
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'technest0123@gmail.com';
    $mail->Password   = 'gmxjvniwypjkdish'; // Gmail App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Sender & Receiver
    $mail->setFrom('technest0123@gmail.com', 'Mailer');
    $mail->addAddress('technest0123@gmail.com');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body    = 'Your email is working!';

    $mail->send();
    echo 'Mail Sent Successfully!';
} catch (Exception $e) {
    echo "Mail Failed: {$mail->ErrorInfo}";
}
?>
