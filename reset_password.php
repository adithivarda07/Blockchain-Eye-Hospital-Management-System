<?php
session_start();
include "db.php";

/* 🔐 CHECK SESSION */
if(!isset($_SESSION['username'])){
    die("❌ Unauthorized access");
}

$password = $_POST['newPass'];

/* 🔐 STRONG PASSWORD VALIDATION */
if(!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@#$%^&*]).{8,}$/", $password)){
    die("❌ Password must be strong (8 chars, uppercase, lowercase, number, symbol)");
}

/* 🔐 HASH PASSWORD */
$newPass = password_hash($password, PASSWORD_DEFAULT);

$username = $_SESSION['username'];

/* 🔐 UPDATE PASSWORD */
$sql = "UPDATE users SET password='$newPass' WHERE username='$username'";

if($conn->query($sql)){
    
    /* 🔥 CLEAR OTP AFTER SUCCESS */
    unset($_SESSION['otp']);
    unset($_SESSION['otp_time']);

    echo "<script>alert('✅ Password updated successfully'); window.location='login.html';</script>";

} else {
    echo "❌ Error updating password";
}
?>