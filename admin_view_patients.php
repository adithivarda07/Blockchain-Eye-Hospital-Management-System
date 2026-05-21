<?php
session_start();
include "db.php";

if(!isset($_SESSION['role']) || 
   ($_SESSION['role'] != "admin" && $_SESSION['role'] != "doctor")){
    echo "Access denied";
    exit();
}

/* FILTER VALUES */
$search = $_GET['search'] ?? '';
$city = $_GET['city'] ?? '';
$status = $_GET['status'] ?? '';
$disease = $_GET['disease'] ?? '';
$gender = $_GET['gender'] ?? '';
$min_age = $_GET['min_age'] ?? '';
$max_age = $_GET['max_age'] ?? '';

/* QUERY */
$sql = "SELECT * FROM patients WHERE 1=1";

if($search != ''){
    $sql .= " AND (name LIKE '%$search%' OR disease LIKE '%$search%')";
}
if($city != ''){
    $sql .= " AND city='$city'";
}
if($status != ''){
    $sql .= " AND status='$status'";
}
if($disease != ''){
    $sql .= " AND disease='$disease'";
}
if($gender != ''){
    $sql .= " AND gender='$gender'";
}
if($min_age != ''){
    $sql .= " AND age >= $min_age";
}
if($max_age != ''){
    $sql .= " AND age <= $max_age";
}

/* 🔥 NO LIMIT */
$sql .= " ORDER BY patient_id DESC";

$result = $conn->query($sql);

/* DROPDOWNS */
$cities = $conn->query("SELECT DISTINCT city FROM patients");
$statuses = $conn->query("SELECT DISTINCT status FROM patients");
$diseases = $conn->query("SELECT DISTINCT disease FROM patients");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin - View Patients</title>

<style>
body { font-family: Arial; background: #f4f6f9; }

.container { width: 90%; margin: auto; }

.filter-box {
    background: white;
    padding: 15px;
    margin-top: 20px;
    border-radius: 10px;
}

input, select {
    padding: 8px;
    margin: 5px;
}

button {
    padding: 8px 15px;
    background: #3498db;
    color: white;
    border: none;
}

/* 🔥 TABLE SCROLL */
.table-box {
    height: calc(100vh - 180px); /* full screen height */
    overflow-y: auto;
    margin-top: 20px;
}

/* TABLE STYLE (doctor-like) */
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

th {
    background: #2c3e50;
    color: white;
    padding: 10px;
    position: sticky;
    top: 0;
}

td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

tr:hover {
    background: #f1f1f1;
}

.btn {
    text-decoration: none;
    padding: 5px 10px;
    border-radius: 5px;
    color: white;
    font-size: 13px;
}

.view { background: #21a2cc; }
.edit { background: #e67e22; }

.back-btn {
    display: inline-block;
    margin-bottom: 10px;
    padding: 8px 15px;
    background: #2c3e50;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    transition: 0.3s;
}

.back-btn:hover {
    background: #1a252f;
}


</style>
</head>

<body>

<div class="container">



<?php
$back_page = ($_SESSION['role'] == 'admin') 
    ? 'admin.php' 
    : 'doctor.php';
?>

<a href="<?= $back_page ?>" class="back-btn">⬅ Back</a>




<h2> Patient Records</h2>

<!-- FILTERS (UNCHANGED) -->
<div class="filter-box">
<form method="GET">

<input type="text" name="search" placeholder="Search name or disease" value="<?= $search ?>">

<select name="city">
<option value="">All Cities</option>
<?php while($c = $cities->fetch_assoc()){ ?>
<option value="<?= $c['city'] ?>" <?= ($city==$c['city'])?'selected':'' ?>>
<?= $c['city'] ?>
</option>
<?php } ?>
</select>

<select name="status">
<option value="">All Status</option>
<?php while($s = $statuses->fetch_assoc()){ ?>
<option value="<?= $s['status'] ?>" <?= ($status==$s['status'])?'selected':'' ?>>
<?= $s['status'] ?>
</option>
<?php } ?>
</select>

<select name="disease">
<option value="">All Diseases</option>
<?php while($d = $diseases->fetch_assoc()){ ?>
<option value="<?= $d['disease'] ?>" <?= ($disease==$d['disease'])?'selected':'' ?>>
<?= $d['disease'] ?>
</option>
<?php } ?>
</select>

<select name="gender">
<option value="">All Gender</option>
<option value="Male" <?= ($gender=="Male")?'selected':'' ?>>Male</option>
<option value="Female" <?= ($gender=="Female")?'selected':'' ?>>Female</option>
</select>

<input type="number" name="min_age" placeholder="Min Age" value="<?= $min_age ?>">
<input type="number" name="max_age" placeholder="Max Age" value="<?= $max_age ?>">

<button type="submit">Apply</button>
<a href="admin_view_patients.php">Reset</a>

</form>
</div>

<!-- 🔥 TABLE VIEW -->
<div class="table-box">

<?php if($result->num_rows > 0){ ?>
<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Age</th>
    <th>Gender</th>
    <th>Disease</th>
    <th>City</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()){ ?>
<tr>
    <td><?= $row['patient_id'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= $row['age'] ?></td>
    <td><?= $row['gender'] ?></td>
    <td><?= $row['disease'] ?></td>
    <td><?= $row['city'] ?></td>
    <td><?= $row['status'] ?></td>
    <td>
        <a class="btn view" href="view_single.php?patient_id=<?= $row['patient_id'] ?>">View</a>


        <a class="btn edit" href="edit_patient.php?patient_id=<?= $row['patient_id'] ?>">Edit</a>
    </td>
</tr>
<?php } ?>

</table>
<?php } else { ?>
<p>No patients found</p>
<?php } ?>

</div>

</div>

</body>
</html>