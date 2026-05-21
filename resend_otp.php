<?php
session_start();
include "db.php";

if(!isset($_SESSION['username']) || !isset($_SESSION['email'])){
    die("Session expired. Try again.");
}

$username = $_SESSION['username'];
$email = $_SESSION['email'];

// 🔁 Generate new OTP
$otp = rand(100000,999999);
$_SESSION['otp'] = $otp;
$_SESSION['otp_time'] = time();

// 📧 PHPMailer
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'your_email@gmail.com';
$mail->Password = 'your_app_password';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('your_email@gmail.com', 'Eye Hospital');
$mail->addAddress($email);

$mail->Subject = "Resent OTP";
$mail->Body = "Your new OTP is: $otp";

if($mail->send()){
    echo "<script>alert('OTP resent successfully'); window.location='verify_otp.html';</script>";
} else {
    echo "❌ Failed to resend OTP";
}
?>