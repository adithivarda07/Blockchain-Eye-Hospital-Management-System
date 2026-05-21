<?php
session_start();
include "db.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != "doctor"){
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

$total = $conn->query("SELECT COUNT(*) as c FROM patients")->fetch_assoc()['c'];
$today = $conn->query("SELECT COUNT(*) as c FROM patients WHERE DATE(created_at)=CURDATE()")->fetch_assoc()['c'];
$disease = $conn->query("SELECT disease, COUNT(*) as c FROM patients GROUP BY disease ORDER BY c DESC LIMIT 1")->fetch_assoc();
$critical = $conn->query("SELECT COUNT(*) as c FROM patients WHERE status='Critical'")->fetch_assoc()['c'];
$recent = $conn->query("SELECT name,disease FROM patients ORDER BY patient_id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html>
<head>
<title>Doctor Dashboard</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

body {
    font-family: 'Inter', sans-serif;
    margin: 0;
    background: linear-gradient(135deg, #2c3e6f, #1e2a4a);
    color: #eaf0ff;
}

/* HEADER */
.header {
    display: flex;
    justify-content: space-between;
    padding: 30px 50px;
}

.header h1 {
    font-size: 26px;
    font-weight: 700;
}

/* BUTTON AREA */
.top-actions {
    display: flex;
    gap: 10px;
}

/* BUTTON */
.btn {
    padding: 10px 16px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
}

.toggle { background: #f59e0b; color: white; }
.back { background: #ef4444; color: white; }

/* DASHBOARD */
.dashboard {
    padding: 0 50px 40px;
}

/* CARDS */
.row {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
}

.card {
    flex: 1;
    background: rgba(255,255,255,0.08);
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.25);
}

.card div:first-child {
    font-size: 18px;
    opacity: 0.85;
}

.card-value {
    font-size: 38px;
    font-weight: 700;
    margin-top: 8px;
}

/* ALERT */
.alert {
    margin-top: 20px;
    padding: 25px;
    border-radius: 16px;
    background: rgba(255, 0, 0, 0.18);
    border-left: 6px solid red;
    font-size: 18px;
}

/* INSIGHT */
.insight {
    margin-top: 20px;
    padding: 25px;
    border-radius: 16px;
    background: rgba(0, 200, 255, 0.18);
    font-size: 18px;
}

/* RECENT */
.recent {
    margin-top: 20px;
    padding: 25px;
    border-radius: 16px;
    background: rgba(255,255,255,0.08);
}

.recent li {
    padding: 8px 0;
    font-size: 16px;
}

/* QUICK */
.quick {
    margin-top: 25px;
    padding: 25px;
    border-radius: 16px;
    background: rgba(255,255,255,0.08);
}

.btn.add { background: #22c55e; color: white; }
.btn.view { background: #3b82f6; color: white; }

@media(max-width:900px){
    .row { flex-direction: column; }
}
</style>

<script>
function toggleMode(){
    document.body.classList.toggle("light-mode");
}

// ✅ BACK FUNCTION
function goBack(){
    window.location.href = "login.html";
}
</script>

</head>

<body>

<div class="header">
    <h1>WELCOME, <?php echo $username; ?></h1>

    <div class="top-actions">
        <button class="btn toggle" onclick="toggleMode()">🌗 Mode</button>

        <!-- ✅ FIXED BACK BUTTON -->
        <button class="btn back" onclick="goBack()">⬅ Back</button>
    </div>
</div>

<div class="dashboard">

    <div class="row">
        <div class="card">
            <div>📊 Today</div>
            <div class="card-value"><?php echo $today; ?></div>
        </div>

        <div class="card">
            <div>👥 Total</div>
            <div class="card-value"><?php echo $total; ?></div>
        </div>

        <div class="card">
            <div>🦠 Top Disease</div>
            <div class="card-value"><?php echo $disease['disease'] ?? 'N/A'; ?></div>
        </div>
    </div>

    <div class="alert">
        <h3>🚨 Critical Alert</h3>
        <p>Critical Patients: <b><?php echo $critical; ?></b></p>
        <p>Immediate action required!</p>
    </div>

    <div class="insight">
        <h3>🧠 Smart Insight</h3>
        <p>Most affected: <b><?php echo $disease['disease'] ?? 'N/A'; ?></b></p>
    </div>

    <div class="recent">
        <h3>📋 Recent Patients</h3>
        <ul>
            <?php while($r = $recent->fetch_assoc()){ ?>
                <li><?php echo $r['name']; ?> - <?php echo $r['disease']; ?></li>
            <?php } ?>
        </ul>
    </div>

    <div class="quick">
        <h3>⚡ Quick Actions</h3>
        <button class="btn add" onclick="window.location='add_patient.php'">➕ Add Patient</button>
        <button class="btn view" onclick="window.location='admin_view_patients.php'">👁 View Patients</button>
    </div>

</div>

</body>
</html>