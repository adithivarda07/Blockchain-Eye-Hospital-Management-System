<?php
session_start();
include "db.php";

if(!isset($_SESSION['username'])){
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username'];

// 🔥 extract patient_id (PAT_SNEH_126 → 126)
$parts = explode("_", $username);
$patient_id = (int) trim(end($parts));

// 🔥 fetch patient data
$sql = "SELECT * FROM patients WHERE patient_id = '$patient_id'";
$result = $conn->query($sql);

if($result && $result->num_rows > 0){
    $patient = $result->fetch_assoc();
} else {
    $patient = null;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Patient Dashboard</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #1e2a4a, #2c3e6f);
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* CARD */
.card {
    background: rgba(255,255,255,0.1);
    padding: 30px;
    border-radius: 15px;
    width: 400px;
    text-align: center;
}

/* BUTTON */
.btn {
    margin-top: 20px;
    padding: 12px;
    width: 100%;
    border: none;
    border-radius: 8px;
    background: #3b82f6;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

.back {
    background: #ef4444;
}
</style>

</head>

<body>

<div class="card">

    <h2>Welcome <?php echo $username; ?></h2>

    <?php if($patient){ ?>

        <p><b>Name:</b> <?php echo $patient['name']; ?></p>
        <p><b>Age:</b> <?php echo $patient['age']; ?></p>
        <p><b>Gender:</b> <?php echo $patient['gender']; ?></p>
        <p><b>Disease:</b> <?php echo $patient['disease']; ?></p>
        <p><b>Status:</b> <?php echo $patient['status']; ?></p>
        <p><b>City:</b> <?php echo $patient['city']; ?></p>

    <?php } else { ?>

        <p style="color:#ffaaaa;">No patient data found</p>

    <?php } ?>

    <button class="btn" onclick="window.location.href='patient_feedback.php'">
        Give Feedback
    </button>

    <button class="btn back" onclick="window.location.href='login.html'">
        ⬅ Back
    </button>

</div>

</body>
</html>