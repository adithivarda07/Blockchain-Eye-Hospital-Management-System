<?php
include "db.php";

$name = $_POST['patient_name'];
$phone = $_POST['phone'];
$concern = $_POST['concern'];
$date = $_POST['appointment_date'];

/* INSERT INTO DB */
$sql = "INSERT INTO appointments(patient_name, phone, concern, appointment_date)
        VALUES('$name', '$phone', '$concern', '$date')";

if($conn->query($sql)){
    
    // 🔥 REDIRECT TO SUCCESS PAGE
    header("Location: appointment_success.php?name=" . urlencode($name) . "&date=" . urlencode($date));
    exit();

} else {
    echo "❌ Error: " . $conn->error;
}
?>