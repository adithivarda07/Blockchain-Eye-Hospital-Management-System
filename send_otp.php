<?php
session_start();
include "db.php";

// 🔒 SAFE INPUT
$username = trim($_POST['username'] ?? '');
$role     = strtolower(trim($_POST['role'] ?? ''));
$email    = trim($_POST['email'] ?? '');

// 🔐 CHECK USER EXISTS
$sql = "SELECT * FROM users WHERE username=? AND role=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $role);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows == 0){
    die("❌ User not found");
}

$user = $res->fetch_assoc();

// 🔥 ADMIN & DOCTOR → EMAIL + OTP REQUIRED
if($role == "admin" || $role == "doctor"){

    if(empty($email)){
        die("❌ Email required");
    }

    if($user['email'] != $email){
        die("❌ Email does not match");
    }

    // 🔢 GENERATE OTP
    $otp = rand(100000,999999);

    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;
    $_SESSION['username'] = $username;

    // 📧 LOAD PHPMailer
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    require 'PHPMailer/src/Exception.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer();

    try {
        $mail->isSMTP();
        $mail->SMTPDebug = 0; // change to 2 for debugging
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'reachakshitha2810@gmail.com';
        $mail->Password = 'ppjivsbwdbxndnac'; // 🔐 app password

        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->CharSet = 'UTF-8';
        $mail->isHTML(false);

        $mail->setFrom('reachakshitha2810@gmail.com', 'Eye Hospital');
        $mail->addAddress($email);

        $mail->Subject = "OTP for Password Reset";
        $mail->Body    = "Your OTP is: $otp";

        if($mail->send()){
            header("Location: verify_otp.html");
            exit();
        } else {
            echo "❌ Mail sending failed";
        }

    } catch (Exception $e) {
        echo "❌ Mail Error: " . $mail->ErrorInfo;
    }
}

// 🔵 PATIENT → DIRECT RESET (NO OTP)
else {
    $_SESSION['username'] = $username;
    header("Location: reset_password.html");
    exit();
}
?>