<?php
session_start();
include "db.php";


if(!isset($_SESSION['user_id'])){
    echo "Login required";
    exit();
}


$username = $_SESSION['username'];

// extract name from username like PAT_KAVY_25 → KAVY
$parts = explode("_", $username);
$name_part = isset($parts[1]) ? $parts[1] : $username;

$sql = "SELECT * FROM patients WHERE LOWER(name) LIKE LOWER('%$name_part%') LIMIT 1";
$result = $conn->query($sql);




if($result->num_rows == 0){
    echo "No patient data found";
    exit();
}

$row = $result->fetch_assoc();

// 🔥 REPORT ID + TIME
$report_id = "EH-" . date("Y") . "-" . rand(1000,9999);
$generated_time = date("d M Y, h:i A");

// 🔥 SMART ALERT
$alert = "";
if($row['bp']=="High"){
    $alert = "⚠ High Blood Pressure detected. Regular monitoring required.";
}
if($row['diabetes']=="Yes"){
    $alert .= "<br>⚠ Diabetes detected. Follow strict diet and medication.";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Patient Report</title>

<style>
body {
    font-family: Arial;
    background: #f4f6f9;
    margin: 0;
    color: #222;
}

/* DARK MODE */
.dark {
    background: #1e293b;
    color: #e2e8f0;
}

.container {
    width: 80%;
    margin: 30px auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
}

.dark .container {
    background: #334155;
    color: white;
}

/* HEADER */
.header {
    text-align: center;
    border-bottom: 2px solid #3498db;
    margin-bottom: 15px;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px 40px;
}

.field {
    padding: 6px 0;
    border-bottom: 1px dashed #ccc;
}

/* STATUS */
.status {
    padding: 4px 10px;
    border-radius: 5px;
    color: white;
}
.recovered { background: #27ae60; }
.improved { background: #f39c12; }
.critical { background: #e74c3c; }

/* ALERT */
.alert {
    background: #fff3cd;
    color: #856404;
    padding: 10px;
    border-radius: 5px;
    margin-top: 10px;
}

/* BUTTONS */
.top-bar {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.btn {
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    color: white;
    cursor: pointer;
}

.pdf-btn { background: #e67e22; }
.mode-btn { background: #3498db; }
.feedback-btn { background: #27ae60; }

/* QR */
.qr {
    text-align: right;
}

/* PDF HIDE */
.no-print {
    display: inline-block;
}

.pdf-mode .no-print {
    display: none !important;
}

.pdf-mode body,
.pdf-mode .container {
    background: white !important;
    color: black !important;
}
.logout-btn {
    background: #e74c3c;
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
function toggleMode(){
    document.body.classList.toggle("dark");
}

function downloadPDF(){
    document.body.classList.add("pdf-mode");

    html2pdf().from(document.querySelector(".container")).save().then(()=>{
        document.body.classList.remove("pdf-mode");
    });
}
</script>

</head>

<body>

<div class="container">

<!-- TOP BAR -->
<div class="top-bar no-print">
    <button class="btn mode-btn" onclick="toggleMode()">🌗 Mode</button>
    <button class="btn pdf-btn" onclick="downloadPDF()">Download PDF</button>
    

    <button class="btn logout-btn" onclick="confirmLogout()">🚪 Logout</button>
     <!-- <button class="btn logout-btn" onclick="window.location.href='logout.php'">🚪 Logout</button>-->
</div>

<!-- HEADER -->
<div class="header">
    <h2>Eye Hospital Medical Report</h2>
    <p><b>Report ID:</b> <?= $report_id ?></p>
    <p><b>Generated:</b> <?= $generated_time ?></p>
</div>

<!-- QR + BLOCKCHAIN -->
<div class="qr">
<img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=PatientID:<?= $row['patient_id'] ?>" />
<br>Scan to verify
</div>

<p>🔐 Data Integrity Verified</p>

<!-- ALERT -->
<?php if($alert != ""){ ?>
<div class="alert"><?= $alert ?></div>
<?php } ?>

<!-- BASIC -->
<h3>Basic Information</h3>
<div class="grid">
<div class="field">Name: <?= htmlspecialchars($row['name']) ?></div>
<div class="field">Age: <?= htmlspecialchars($row['age']) ?></div>
<div class="field">Gender: <?= htmlspecialchars($row['gender']) ?></div>
<div class="field">Phone: <?= htmlspecialchars($row['phone']) ?></div>
<div class="field">City: <?= htmlspecialchars($row['city']) ?></div>
</div>

<!-- MEDICAL -->
<h3>Medical Details</h3>
<div class="grid">
<div class="field">Complaint: <?= htmlspecialchars($row['complaint']) ?></div>
<div class="field">Duration: <?= htmlspecialchars($row['duration']) ?></div>
<div class="field">Vision: <?= $row['vision_od'] ?> / <?= $row['vision_os'] ?></div>
<div class="field">IOP: <?= htmlspecialchars($row['iop']) ?></div>
</div>

<!-- TREATMENT -->
<h3>Treatment</h3>
<div class="grid">
<div class="field">Disease: <?= htmlspecialchars($row['disease']) ?></div>
<div class="field">Treatment: <?= htmlspecialchars($row['treatment']) ?></div>
<div class="field">Cost: ₹<?= htmlspecialchars($row['treatment_cost']) ?></div>
<div class="field">Status: 
<span class="status <?= strtolower($row['status']) ?>">
<?= htmlspecialchars($row['status']) ?>
</span>
</div>
<div class="field">Follow-up: <?= htmlspecialchars($row['followup']) ?></div>
</div>

<!-- DOCTOR SIGN -->
<br><br>
<div>
------------------------<br>
Dr. Ramesh Kumar<br>
Consultant Ophthalmologist<br>
License ID: DOC-4587
</div>

<!-- FOOTER -->
<div style="text-align:center;margin-top:20px;color:gray;">
✔ Digitally Generated & Secure Report
</div>

<!-- FEEDBACK -->
<div style="text-align:center;margin-top:20px;" class="no-print">
<a href="patient_feedback.php">
<button class="btn feedback-btn">Give Feedback</button>
</a>
</div>

</div>

<script>
function confirmLogout(){
    if(confirm("Are you sure you want to logout?")){
        window.location.href = "logout.php";
    }
}
</script>
</body>
</html>