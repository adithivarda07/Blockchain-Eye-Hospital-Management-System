<?php
session_start();
include "db.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    echo "Access denied";
    exit();
}

$result = $conn->query("
SELECT f.*, p.name 
FROM feedback f
JOIN patients p ON f.patient_id = p.patient_id
ORDER BY f.date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Feedback Dashboard</title>

<style>
body { font-family: Arial; background: #f4f6f9; padding: 30px; }

.card {
    background: white;
    padding: 15px;
    margin: 10px 0;
    border-radius: 10px;
}
</style>
</head>

<body>

<h2>Patient Feedback</h2>

<?php
while($row = $result->fetch_assoc()){
    echo "
    <div class='card'>
        <b>{$row['name']}</b> ⭐ {$row['rating']} <br>
        {$row['feedback_text']} <br>
        <small>{$row['date']}</small>
    </div>
    ";
}
?>

</body>
</html>