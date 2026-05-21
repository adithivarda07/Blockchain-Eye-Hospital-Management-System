<?php
include "db.php";

$patient_id = $_GET['id'] ?? '';

if(!$patient_id){
    die("Invalid QR");
}

// run python verification
$output = shell_exec("python verify_single.py $patient_id 2>&1");

// UI
echo "<h2>🔍 Verification Result</h2>";
echo "<pre>$output</pre>";
?>