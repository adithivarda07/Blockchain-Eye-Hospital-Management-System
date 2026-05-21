<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = strtolower($_POST['role']);

    // 🔍 GET USER FIRST (no password check here)
    $sql = "SELECT * FROM users WHERE username='$username' AND LOWER(role)='$role'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        // 🔐 VERIFY PASSWORD (IMPORTANT)
        if (password_verify($password, $user['password'])) {

            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = strtolower($user['role']);
            $_SESSION['user_id'] = $user['user_id'];

            // 🔥 VERY IMPORTANT (for patient dashboard)
           $_SESSION['patient_id'] = $user['patient_id'];

            // 🔀 REDIRECT
            if ($_SESSION['role'] == "doctor") {
                header("Location: doctor.php");
            }
            elseif ($_SESSION['role'] == "admin") {
                header("Location: admin.php");
            }
            else {
                header("Location: patient_dashboard.php");
            }
            exit();

        } else {
            echo "<script>alert('Wrong Password'); window.location='login.html';</script>";
        }

    } else {
        echo "<script>alert('User not found'); window.location='login.html';</script>";
    }
}
?>