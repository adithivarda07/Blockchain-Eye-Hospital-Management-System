<?php
session_start();

if(!isset($_SESSION['otp'])){
    die("Session expired. Try again.");
}

$user_otp = $_POST['otp'];

if($user_otp == $_SESSION['otp']){
    
    // ✅ correct OTP
    header("Location: reset_password.html");
    exit();

} else {
    echo "<script>alert('❌ Invalid OTP'); window.location='verify_otp.html';</script>";
}
?>
