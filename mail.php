<?php

session_start();
$email = $_SESSION['email'];
$name = $_SESSION['name'];


require_once('PHPMailer/PHPMailerAutoload.php');
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl';
$mail->Host = 'smtp.gmail.com';
$mail->Port = '465';
$mail->isHTML();
$mail->Username = 'thepictureperf@gmail.com';
$mail->Password = 'PicturePerf7';

$mail->FromName = "Perfect Plate";
$mail->addReplyTo("thepictureperf@gmail.com", "Reply");
//Send HTML or Plain Text email
$mail->isHTML(true);

$mail->Subject = "Welcome to Perfect Plate";
$mail->Body = "<p>Thank you for siging up!\n Enjoy our food services.</p>";
$mail->AddAddress($email);

try {
    $mail->send();
    header("Location: index.php");
} catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}
