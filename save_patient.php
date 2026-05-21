<?php
include "db.php";

// ================= BASIC =================
$name = $_POST['name'] ?? '';
$age = $_POST['age'] ?? '';
$gender = $_POST['gender'] ?? '';
$phone = $_POST['phone'] ?? '';

// ================= REGION =================
$city = $_POST['city'] ?? '';
$district = $_POST['district'] ?? '';
$state = $_POST['state'] ?? '';
$pincode = $_POST['pincode'] ?? '';

// OCCUPATION
$occupation = ($_POST['occupation'] == "Other") 
    ? ($_POST['occupation_other'] ?? '') 
    : ($_POST['occupation'] ?? '');

// ================= CLINICAL =================
$complaint = ($_POST['complaint'] == "Other") 
    ? ($_POST['complaint_other'] ?? '') 
    : ($_POST['complaint'] ?? '');

$duration = $_POST['duration'] ?? '';
$vision_od = $_POST['vision_od'] ?? '';
$vision_os = $_POST['vision_os'] ?? '';
$iop = $_POST['iop'] ?? '';

// ================= DIAGNOSIS =================
$disease = ($_POST['disease'] == "Other") 
    ? ($_POST['disease_other'] ?? '') 
    : ($_POST['disease'] ?? '');

// ================= RISK FACTORS =================
$diabetes = $_POST['diabetes'] ?? '';
$bp = $_POST['bp'] ?? '';
$screen_time = $_POST['screen_time'] ?? '';
$smoking = $_POST['smoking'] ?? '';
$uv_exposure = $_POST['uv_exposure'] ?? '';

// ================= TREATMENT =================
$treatment = $_POST['treatment'] ?? '';
$treatment_details = $_POST['treatment_details'] ?? '';
$cost = $_POST['treatment_cost'] ?? '';

// ================= OUTCOME =================
$status = $_POST['status'] ?? '';
$followup = $_POST['followup'] ?? '';

// ================= METADATA =================
$doctor_id = $_POST['doctor_id'] ?? '';
$visit_type = $_POST['visit_type'] ?? '';

// ================= INSERT PATIENT =================
$sql = "INSERT INTO patients(
name, age, gender, phone,
city, district, state, pincode, occupation,
complaint, duration, vision_od, vision_os, iop,
disease,
diabetes, bp, screen_time, smoking, uv_exposure,
treatment, treatment_details, treatment_cost,
status, followup, doctor_id, visit_type
)
VALUES(
'$name','$age','$gender','$phone',
'$city','$district','$state','$pincode','$occupation',
'$complaint','$duration','$vision_od','$vision_os','$iop',
'$disease',
'$diabetes','$bp','$screen_time','$smoking','$uv_exposure',
'$treatment','$treatment_details','$cost',
'$status','$followup','$doctor_id','$visit_type'
)";

// ================= EXECUTE =================
if($conn->query($sql) === TRUE){

    $patient_id = $conn->insert_id;

    // ================= 🔥 BLOCKCHAIN CALL =================
$data = $name . $age . $disease . $treatment;

// ✅ Python full path
$python = "\"C:\\Users\\Adithi varda\\AppData\\Local\\Microsoft\\WindowsApps\\python.exe\"";

// ✅ Script full path
$script = __DIR__ . "\\blockchain_runner.py";

$safe_data = escapeshellarg($data);

$command = "$python $script $patient_id $safe_data";

// 🔍 DEBUG (IMPORTANT)
//echo "<pre>COMMAND: $command</pre>";

// 🔥 EXECUTE
$output = shell_exec($command . " 2>&1");
if(!$output){
    $output = " No output from Python\nCheck:\n1. Ganache running\n2. Python path\n3. Script location";
}

    // ================= CREATE LOGIN =================
    $username = $patient_id;
    $default_pass = "PAT@" . $patient_id;

    $hashed_password = password_hash($default_pass, PASSWORD_DEFAULT);

    $conn->query("INSERT INTO users(username,password,role)
    VALUES('$username','$hashed_password','patient')");

    // ================= SUCCESS =================
    echo "
    <div style='text-align:center; margin-top:50px; font-family:Arial'>
        <h2 style='color:green;'>Patient Added Successfully ✅</h2>

        <p><b>Patient ID:</b> $patient_id</p>
        <p><b>Login Username:</b> $username</p>
        <p><b>Password:</b> $default_pass</p>

        <p style='color:#3498db;'>🔗 Blockchain Processing:</p>
        <pre>$output</pre>

        <br>
        <a href='doctor.php' style='padding:10px 20px; background:#2c3e50; color:white; text-decoration:none; border-radius:5px;'>Back to Dashboard</a>
    </div>
    ";

} else {
    echo "Error: " . $conn->error;
}
?>