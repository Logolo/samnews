<?php
require_once(CLASSES_PATH . 'phpmailer/class.phpmailer.php');
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug  = 1;
$mail->SMTPAuth   = true;
$mail->Host       = "localhost";
$mail->Port       = 25;
$mail->Username   = "noreply@yoursite.com";
$mail->Password   = "smtppassword";
$mail->SetFrom('noreply@yoursite.com', 'yourname');
$mail->AddReplyTo('noreply@yoursite.com','yourname');
?>