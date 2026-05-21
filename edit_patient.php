<?php
include "db.php";

if (!isset($_GET['patient_id'])) {
    echo "No patient selected";
    exit();
}

$id = $_GET['patient_id'];

/* Fetch patient */
$res = $conn->query("SELECT * FROM patients WHERE patient_id='$id'");
$row = $res->fetch_assoc();

/* UPDATE PATIENT */
if (isset($_POST['update_patient'])) {

    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $city = $_POST['city'];
    $district = $_POST['district'];

    $conn->query("UPDATE patients SET 
        name='$name',
        age='$age',
        gender='$gender',
        city='$city',
        district='$district'
        WHERE patient_id='$id'
    ");

    // 🔗 Blockchain update
    $data = $name . $age . $gender . $city;
    exec("python blockchain_runner.py \"$id\" \"$data\"");

    // 🔄 Refresh page
    header("Location: edit_patient.php?patient_id=$id");
    exit();
}

/* ADD FOLLOW-UP */
if (isset($_POST['add_visit'])) {

    $disease = $_POST['disease'];
    $complaint = $_POST['complaint'];
    $treatment = $_POST['treatment'];
    $status = $_POST['status'];
    $visit_type = $_POST['visit_type'];

    // Combine into notes (since your table only has notes column)
    $notes = "Disease: $disease | Complaint: $complaint | Treatment: $treatment | Status: $status | Type: $visit_type";

    $conn->query("INSERT INTO patient_visits 
    (patient_id, visit_date, notes)
    VALUES ('$id', NOW(), '$notes')");

    header("Location: view_single.php?patient_id=$id");
}
?>

<!DOCTYPE html>

<html>
<head>
<title>Edit Patient</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(to right, #eef2f7, #dfe9f3);
    margin: 0;
}

.container {
    width: 420px;
    margin: 40px auto;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

h1 {
    text-align: center;
    color: #34495e;
}

h2, h3 {
    text-align: center;
    color: #2c3e50;
}

input, select, textarea {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border-radius: 8px;
    border: 1px solid #ccc;
    transition: 0.2s;
}

input:focus, select:focus, textarea:focus {
    border-color: #3498db;
    outline: none;
}

button {
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    cursor: pointer;
    transition: 0.3s;
}

.update {
    background: #27ae60;
    color: white;
}

.update:hover {
    background: #219150;
}

.visit {
    background: #3498db;
    color: white;
}

.visit:hover {
    background: #2c80b4;
}

</style>

</head>

<body>

<h1>🏥 Patient Management</h1>

<div class="container">

<div class="card">
<h2>Edit Patient</h2>

<form method="POST">
<input type="text" name="name" value="<?= $row['name'] ?>" required>
<input type="number" name="age" value="<?= $row['age'] ?>" required>

<select name="gender">
<option <?= $row['gender']=="Male"?"selected":"" ?>>Male</option>
<option <?= $row['gender']=="Female"?"selected":"" ?>>Female</option>
</select>

<input type="text" name="city" value="<?= $row['city'] ?>" required>
<input type="text" name="district" value="<?= $row['district'] ?>" required>

<button class="update" name="update_patient">Update Patient</button>

</form>
</div>

<div class="card">
<h3>Add Follow-up Visit</h3>

<form method="POST">
<input type="text" name="disease" placeholder="Disease" required>
<textarea name="complaint" placeholder="Complaint"></textarea>
<textarea name="treatment" placeholder="Treatment"></textarea>

<select name="status">
<option>Cured</option>
<option>Improving</option>
<option>Critical</option>
</select>

<select name="visit_type">
<option>First Visit</option>
<option>Follow-up</option>
</select>

<button class="visit" name="add_visit">Add Visit</button>

</form>
</div>

</div>

</body>
</html>
