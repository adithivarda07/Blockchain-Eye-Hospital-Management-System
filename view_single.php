<?php
session_start();
include "db.php";

/* 🔥 Decide ID */
if (isset($_GET['self'])) {

    if (!isset($_SESSION['patient_id'])) {
        echo "Please login first";
        exit();
    }

    $id = $_SESSION['patient_id'];

} else {

    if (!isset($_GET['patient_id'])) {
        echo "No patient selected";
        exit();
    }

    $id = $_GET['patient_id'];
}

/* ================= PATIENT INFO ================= */
$res = $conn->query("SELECT * FROM patients WHERE patient_id='$id'");
$row = $res->fetch_assoc();

if (!$row) {
    echo "Patient not found";
    exit();
}

/* ================= GET LATEST VISIT ================= */
$latest = $conn->query("SELECT * FROM patient_visits 
                       WHERE patient_id='$id' 
                       ORDER BY visit_date DESC 
                       LIMIT 1");

$latest_visit = $latest->fetch_assoc();

/* helper */
function show($value){
    return $value ? $value : "—";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Patient Details</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI';
    background: linear-gradient(120deg, #1f2a40, #3a4f7a);
    color: white;
}

.container {
    width: 85%;
    margin: auto;
    padding: 20px;
}

.card {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(15px);
    padding: 20px;
    margin-top: 20px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
}

h2, h3 {
    margin-bottom: 10px;
}

.visit {
    background: rgba(255,255,255,0.15);
    padding: 12px;
    margin: 10px 0;
    border-radius: 10px;
}

.status {
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 5px;
}

.cured { background: #2ecc71; color: white; }
.improving { background: #f1c40f; color: black; }
.critical { background: #e74c3c; color: white; }

.back {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 15px;
    background: rgba(44,62,80,0.9);
    color: white;
    text-decoration: none;
    border-radius: 6px;
}
</style>
</head>

<body>

<div class="container">

<!-- PATIENT DETAILS -->
<div class="card">
    <h2>Patient Details</h2>

<hr>

<h3>Location</h3>
<p>City: <?= show($row['city']) ?></p>
<p>District: <?= show($row['district']) ?></p>
<p>State: <?= show($row['state']) ?></p>

<hr>

<h3>Clinical Details</h3>
<p>Complaint: <?= show($row['complaint']) ?></p>
<p>Vision OD: <?= show($row['vision_od']) ?></p>
<p>Vision OS: <?= show($row['vision_os']) ?></p>

<hr>

<h3>Diagnosis</h3>
<p>Disease: <?= show($row['disease']) ?></p>

<hr>

<h3>Treatment</h3>
<p><?= show($row['treatment']) ?></p>
<p>Cost: ₹<?= show($row['treatment_cost']) ?></p>

<hr>

<h3>Outcome</h3>
<p>Status: <?= show($row['status']) ?></p>

</div>

<!-- LATEST VISIT -->
<div class="card">
<h3>Latest Status</h3>

<?php if ($latest_visit) {

    $status = strtolower($latest_visit['status']);
    $class = "";

    if ($status == "cured") $class = "cured";
    elseif ($status == "improving") $class = "improving";
    elseif ($status == "critical") $class = "critical";

    echo "
        <p><b>Date:</b> {$latest_visit['visit_date']}</p>
        <p><b>Disease:</b> {$latest_visit['disease']}</p>
        <p><b>Status:</b> <span class='status $class'>{$latest_visit['status']}</span></p>
    ";
} else {
    echo "<p>No visits yet</p>";
}
?>

</div>

<!-- VISIT HISTORY -->
<div class="card">
<h3>Visit History</h3>

<?php
$visits = $conn->query("SELECT * FROM patient_visits WHERE patient_id='$id' ORDER BY visit_date DESC");

if ($visits->num_rows > 0) {
    while($v = $visits->fetch_assoc()){
        echo "
        <div class='visit'>
            <b>Date:</b> {$v['visit_date']} <br>
            <b>Disease:</b> {$v['disease']} <br>
            <b>Treatment:</b> {$v['treatment']} <br>
            <b>Status:</b> {$v['status']}
        </div>
        ";
    }
} else {
    echo "<p>No visits found</p>";
}
?>

</div>

<!-- BACK -->
<div class="card">
<a class="back" href="<?php echo isset($_GET['self']) ? 'patient_dashboard.php' : 'admin_view_patients.php'; ?>">
⬅ Back
</a>
</div>

</div>

</body>
</html>