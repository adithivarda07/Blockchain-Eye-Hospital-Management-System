<?php
include "db.php";

$id = $_POST['id'];

// UPDATE BASIC
$conn->query("UPDATE patients SET 
name='{$_POST['name']}',
age='{$_POST['age']}',
city='{$_POST['city']}',
update_count = update_count + 1
WHERE patient_id=$id");

// INSERT VISIT (NO CHANGE)
$disease = $_POST['disease'];
$complaint = $_POST['complaint'] ?? '';
$treatment = $_POST['treatment'] ?? '';
$treatment_details = $_POST['treatment_details'] ?? '';
$cost = $_POST['treatment_cost'] ?? 0;
$status = $_POST['status'];
$visit_type = $_POST['visit_type'] ?? 'Follow-up';

$sql = "INSERT INTO patient_visits(
patient_id, disease, complaint, treatment, treatment_details, treatment_cost, status, visit_type
)
VALUES(
'$id','$disease','$complaint','$treatment','$treatment_details','$cost','$status','$visit_type'
)";

if($conn->query($sql)){
    header("Location: view_single.php?id=$id");
} else {
    echo "Error: " . $conn->error;
}
?>