<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name = $_POST['name'];
    $patient_id = $_POST['patient_id'];
    $password = $_POST['pass'];

    // 🔐 hash password
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // 🔤 first 4 letters of name
    $prefix = strtoupper(substr($name, 0, 4));

    // 🆔 create username
    $username = "PAT_" . $prefix . "_" . $patient_id;

    // ✅ insert into users table
    $sql = "INSERT INTO users(username, password, role)
            VALUES('$username', '$hashed', 'patient')";

    if($conn->query($sql)){
        echo "<h2 style='color:green;'>Account Created Successfully</h2>";
        echo "Your Patient ID: <b>$username</b>";
    } else {
        echo "Error: " . $conn->error;
    }

} else {
    echo "Form not submitted properly";
}
?>