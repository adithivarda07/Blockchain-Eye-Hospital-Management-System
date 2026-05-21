<?php
session_start();
include "db.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("Location: login.php");
    exit();
}
$output = shell_exec("python verify_integrity.py 2>&1");
$username = $_SESSION['username'];
?>

<?php
// TOTAL PATIENTS
$totalPatients = $conn->query("SELECT COUNT(*) as total FROM patients")->fetch_assoc()['total'];

// TOTAL REVENUE
$totalRevenue = $conn->query("SELECT SUM(treatment_cost) as total FROM patients")->fetch_assoc()['total'] ?? 0;

// MOST COMMON DISEASE
$disease = $conn->query("
    SELECT disease, COUNT(*) as count 
    FROM patients 
    GROUP BY disease 
    ORDER BY count DESC LIMIT 1
")->fetch_assoc();

// AVERAGE COST
$avgCost = $conn->query("SELECT AVG(treatment_cost) as avg FROM patients")->fetch_assoc()['avg'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

body {
    font-family: 'Inter', sans-serif;
    margin: 0;
    background: url('eye_bg.jpg') no-repeat center center/cover;
    color: #eaf0ff;
    transition: 0.3s;
}

/* OVERLAY */
body::before {
    content: "";
    position: fixed;
    width: 100%;
    height: 100%;
    background: rgba(20,30,60,0.75);
    backdrop-filter: blur(6px);
    z-index: -1;
}

/* ✅ FULL LIGHT MODE FIX */
.light-mode {
    background: #f5f7fb !important;
    color: #1afdf1 !important;
}

.light-mode .card {
    background: #ffffff !important;
    color: #222 !important;
}

.light-mode .header h1 {
    color: #222 !important;
}

.light-mode .patient-list b {
    color: #2563eb !important;
}

/* HEADER */
.header {
    display: flex;
    justify-content: space-between;
    padding: 25px 50px;
}

.header h1 {
    font-size: 30px;
    font-weight: 700;
}

/* BUTTONS */
.top-actions {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 12px 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
}

.toggle { background: #f59e0b; color: white; }
.back { background: #ef4444; color: white; }
.logout { background: #111; color: white; }

/* DASHBOARD */
.dashboard {
    padding: 0 50px 40px;
}

/* CARD */
.card {
    background: rgba(255,255,255,0.08);
    padding: 30px;
    border-radius: 18px;
    margin-bottom: 25px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.25);
    transition: 0.3s;
}

.card h2 {
    font-size: 24px;
    margin-bottom: 15px;
}

/* BUTTONS */
.card button {
    margin-top: 10px;
    padding: 12px 16px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

.open { background: #10b981; color: white; }
.view { background: #3b82f6; color: white; }
.create { background: #8b5cf6; color: white; }

/* LIST */
.patient-list {
    font-size: 18px;
    line-height: 1.8;
    max-height: 300px;
    overflow-y: auto;
    padding-right: 10px;
}

/* SCROLLBAR */
.patient-list::-webkit-scrollbar {
    width: 6px;
}
.patient-list::-webkit-scrollbar-thumb {
    background: #60a5fa;
    border-radius: 10px;
}

.patient-list b {
    color: #60a5fa;
}

.analytics-box {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

.btn-hero {
    position: relative;
    padding: 16px 32px;
    font-size: 18px;
    font-weight: bold;
    color: white;
    text-decoration: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #00c853, #00e676);
    overflow: hidden;
    z-index: 1;
    transition: 0.3s;
}

/* second button */
.btn-hero.secondary {
    background: linear-gradient(135deg, #00bcd4, #26c6da);
}

/* hover glow */
.btn-hero:hover {
    transform: scale(1.08);
    box-shadow: 0 0 20px #00e676, 0 0 40px #00c853;
}

/* animated border */
.btn-hero::before {
    content: "";
    position: absolute;
    inset: -3px;
    background: linear-gradient(270deg, #00e676, #00c853, #00e676);
    background-size: 400%;
    z-index: -1;
    border-radius: 14px;
    animation: moveBorder 5s linear infinite;
}

.btn-hero::after {
    content: "";
    position: absolute;
    inset: 3px;
    background: inherit;
    border-radius: 12px;
    z-index: -1;
}

@keyframes moveBorder {
    0% { background-position: 0% }
    100% { background-position: 400% }
}

/* APPROVE BUTTON */
.approve-btn {
    background: #10b981;
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    margin-left: 8px;
    transition: 0.3s;
}

.approve-btn:hover {
    background: #059669;
}

/* REJECT BUTTON */
.reject-btn {
    background: #ef4444;
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    margin-left: 5px;
    transition: 0.3s;
}

.reject-btn:hover {
    background: #dc2626;
}
/* DEFAULT */
.tamper-box {
    position: relative;
    margin: 20px;
    padding: 18px;
    border-radius: 12px;
    background: rgba(0,0,0,0.25);
    color: white;
    overflow: hidden;
}

/* glow border */
.tamper-box::before {
    content: "";
    position: absolute;
    inset: 0;
    padding: 6px;
    border-radius: 12px;
    background-size: 400%;
    animation: glowMove 6s linear infinite;

    -webkit-mask:
        linear-gradient(#fff 0 0) content-box,
        linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
}
/* 🚨 PULSE ALERT */
.tamper-box.danger {
    animation: pulse 1.2s infinite;
}

/* animation */
@keyframes pulse {
    0% {
        box-shadow: 0 0 5px rgba(255,0,0,0.6);
    }
    50% {
        box-shadow: 0 0 30px rgba(255,0,0,1);
    }
    100% {
        box-shadow: 0 0 5px rgba(255,0,0,0.6);
    }
}

/* states */
.tamper-box.safe::before {
    background: linear-gradient(270deg, #22c55e, #16a34a, #22c55e);
}

.tamper-box.demo::before {
    background: linear-gradient(270deg, #3b82f6, #2563eb, #3b82f6);
}

.tamper-box.danger::before {
    background: linear-gradient(270deg, #ef4444, #dc2626, #ef4444);
}

/* slight alert for danger */
.tamper-box.danger {
    box-shadow: 0 0 12px rgba(239, 68, 68, 0.5);
}

@keyframes glowMove {
    0% { background-position: 0% }
    100% { background-position: 400% }
}

</style>

<script>
function toggleMode(){
    document.body.classList.toggle("light-mode");
}
</script>

</head>

<body>

<div class="header">
    <h1>👁 Admin Dashboard</h1>

    <div class="top-actions">
        <button class="btn toggle" onclick="toggleMode()">🌗 Mode</button>
<button class="btn back" onclick="confirmBack()">⬅ Back</button>
<button class="btn logout" onclick="confirmLogout()">Logout</button>
    </div>
</div>

<div class="dashboard">



<div class="analytics-box">

    <a href="https://app.powerbi.com/groups/me/reports/6934dda4-70e8-4823-8197-29eac0e45494/0791718bee2cf3756ef4?experience=power-bi" target="_blank" class="btn-hero">
        📊 Open Analytics Dashboard
    </a>

<a href="dashboard1.pbix" download>
    ⬇ Download Power BI report
</a>



</div>
<!-- 📅 APPOINTMENTS -->
<div class="card">
    <h2>📅 Appointments</h2>

    <div class="patient-list">
        <?php
        $appointments = $conn->query("SELECT * FROM appointments ORDER BY created_at DESC");

        if($appointments && $appointments->num_rows > 0){
            while($row = $appointments->fetch_assoc()){
                echo "<b>".$row['patient_name']."</b> | ";
                echo $row['phone']." | ";
                echo $row['appointment_date']." | ";
                echo $row['status']." ";
if($row['status'] == "Pending"){
    echo "<a class='approve-btn' href='update_status.php?id=".$row['id']."&status=Approved'>Approve</a>";
    echo "<a class='reject-btn' href='update_status.php?id=".$row['id']."&status=Rejected'>Reject</a>";
}
                echo "<br><br>";
            }
        } else {
            echo "No appointments yet";
        }
        ?>
    </div>
</div>
    
    <!-- MANAGE -->
    <div class="card">
        <h2>👥 Manage Patients</h2>
        <button class="view" onclick="window.location.href='admin_view_patients.php'">View Patients</button>
        <button class="create" onclick="window.location.href='create_user.php'">+ Create Doctor/Admin</button>
    </div>

   

<!-- 🔐 Tampering Detection -->
<div class="<?php echo $boxClass; ?>">

<?php
$boxClass = "tamper-box";
$message = "";
$details = "";

if(strpos($output, "No Tampering") !== false){
    $boxClass .= " safe";
    $message = "✅ No Tampering Detected";
}
elseif(strpos($output, "Blockchain not connected") !== false){
    $boxClass .= " demo";
    $message = "🔒 Data Integrity Check Enabled";
}
else{
    $boxClass .= " danger";
    $message = "🚨 Data Tampering Detected!";

    $lines = explode("\n", $output);

    foreach($lines as $line){
        if(strpos($line, "::") !== false){
            list($id, $name) = explode("::", $line);
            $details .= "⚠ Patient ID: $id | Name: $name <br>";
        }
    }
}
?>

<div class="<?php echo $boxClass; ?>">
    <h3><?php echo $message; ?></h3>

    <?php if($details != ""): ?>
        <div style="margin-top:10px;">
            <?php echo $details; ?>
        </div>
    <?php endif; ?>
</div>

</div>





    <!-- ✅ ALL PATIENTS FIXED -->
    <div class="card">
        <h2>🧾 All Patients</h2>

        <div class="patient-list">
            <?php
            $res = $conn->query("SELECT name, city, status FROM patients");

            while($row = $res->fetch_assoc()){
                echo $row['name'] . " | " . $row['city'] . " | " . $row['status'] . "<br>";
            }
            ?>
        </div>
    </div>


<div class="card">

    <h2>📊 Analytics Overview</h2>

    <div style="display:grid; grid-template-columns: repeat(4,1fr); gap:15px; margin-top:15px;">

        <div style="background:#3498db; padding:15px; border-radius:10px; color:white;">
            <h3><?= $totalPatients ?></h3>
            <p>Total Patients</p>
        </div>

        <div style="background:#27ae60; padding:15px; border-radius:10px; color:white;">
            <h3>₹<?= $totalRevenue ?></h3>
            <p>Total Revenue</p>
        </div>

        <div style="background:#9b59b6; padding:15px; border-radius:10px; color:white;">
            <h3><?= $disease['disease'] ?? 'N/A' ?></h3>
            <p>Top Disease</p>
        </div>

        <div style="background:#f39c12; padding:15px; border-radius:10px; color:white;">
            <h3>₹<?= round($avgCost) ?></h3>
            <p>Avg Cost</p>
        </div>

    </div>

</div>


    <!-- FEEDBACK -->
<div class="card">
    <h2>💬 Patient Feedback</h2>

    <div class="patient-list">
        <?php
        // fetch feedback
        $feedbacks = $conn->query("
            SELECT username, comments, rating 
            FROM feedback 
            ORDER BY feedback_id DESC
        ");

        if($feedbacks && $feedbacks->num_rows > 0){
            while($f = $feedbacks->fetch_assoc()){

                // safe rating
                $rating = (int)$f['rating'];
                if($rating <= 0) $rating = 1;

                // generate stars
                $stars = str_repeat("⭐", $rating);

echo "<div style='background:#ffffff;color:#000;padding:15px;margin:12px 0;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.2);'>";
echo "<b style='font-size:18px;color:#2c3e50;'>" . htmlspecialchars($f['username']) . "</b><br>";
echo "<span style='color:#f1c40f;font-size:20px;'>" . $stars . "</span>";
                echo "<span style='font-weight:bold;'>(" . $rating . "/4)</span><br>";
echo "<p style='margin-top:5px;'>" . htmlspecialchars($f['comments']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No feedback available</p>";
        }
        ?>
    </div>
</div>

</div>
<script>
function confirmBack(){
    if(confirm("⚠ Are you sure you want to go back?")){
        window.location.href = "login.html";
    }
}

function confirmLogout(){
    if(confirm("⚠ Are you sure you want to logout?")){
        window.location.href = "logout.php";
    }
}
</script>
</body>
</html>