<?php include "db.php"; ?>


<!DOCTYPE html>
<html>
<head>
<title>Add Patient</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(120deg, #1f2a40, #3a4f7a);
}

/* CENTER */
.wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* CARD */
.card {
    background: white;
    width: 750px;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0px 15px 40px rgba(0,0,0,0.3);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

/* GRID */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.full {
    grid-column: span 2;
}

/* INPUT */
input, select {
    width: 100%;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

/* BUTTON */
button {
    width: 100%;
    padding: 14px;
    background: #2c3e50;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

button:hover {
    background: #1a252f;
}

.back-btn {
    display: inline-block;
    margin: 15px;
    padding: 8px 15px;
    background: #2c3e50;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}

.back-btn:hover {
    background: #1a252f;
}


</style>

<script>
function handleOther(selectId, inputId){
    let select = document.getElementById(selectId);
    let input = document.getElementById(inputId);

    if(select.value === "Other"){
        input.style.display = "block";
    } else {
        input.style.display = "none";
        input.value = "";
    }
}
</script>

</head>

<body>


<div class="wrapper">

<div class="card">
<a href="doctor.php" class="back-btn">⬅ Back</a>
<h2>Add Patient</h2>

<form action="save_patient.php" method="POST">

<div class="form-grid">

<input type="text" name="name" placeholder="Full Name" required>
<input type="number" name="age" placeholder="Age" required>

<select name="gender">
<option value="">Gender</option>
<option>Male</option>
<option>Female</option>
<option>Other</option>
</select>

<input type="text" name="phone" placeholder="Phone">

<!-- REGION -->
<input type="text" name="city" placeholder="City">
<input type="text" name="district" placeholder="District">
<input type="text" name="state" placeholder="State">
<input type="text" name="pincode" placeholder="Pincode">

<!-- OCCUPATION -->
<select name="occupation" id="occupation" onchange="handleOther('occupation','occ_other')">
<option value="">Occupation</option>
<option>Student</option>
<option>IT Employee</option>
<option>Farmer</option>
<option>Business</option>
<option>Other</option>
</select>

<input type="text" id="occ_other" name="occupation_other" placeholder="Enter Occupation" style="display:none">

<!-- DISEASE -->
<select name="disease" id="disease" onchange="handleOther('disease','dis_other')" required>
<option value="">Disease</option>
<option>Cataract</option>
<option>Glaucoma</option>
<option>Myopia</option>
<option>Hypermetropia</option>
<option>Conjunctivitis</option>
<option>Dry Eye</option>
<option>Other</option>
</select>

<input type="text" id="dis_other" name="disease_other" placeholder="Enter Disease" style="display:none">

<!-- COMPLAINT -->
<select name="complaint" id="complaint" onchange="handleOther('complaint','comp_other')">
<option value="">Complaint</option>
<option>Blurred Vision</option>
<option>Eye Pain</option>
<option>Redness</option>
<option>Dryness</option>
<option>Watering</option>
<option>Other</option>
</select>

<input type="text" id="comp_other" name="complaint_other" placeholder="Enter Complaint" style="display:none">

<select name="duration">
<option value="">Duration</option>
<option>Days</option>
<option>Weeks</option>
<option>Months</option>
</select>

<input type="number" name="iop" placeholder="Eye Pressure (IOP)">

<select name="diabetes">
<option value="">Diabetes</option>
<option>Yes</option>
<option>No</option>
</select>

<select name="bp">
<option value="">Hypertension</option>
<option>Yes</option>
<option>No</option>
</select>

<select name="screen_time">
<option value="">Screen Time</option>
<option>0-2 hrs</option>
<option>3-5 hrs</option>
<option>6-8 hrs</option>
<option>8+ hrs</option>
</select>

<select name="smoking">
<option value="">Smoking</option>
<option>Yes</option>
<option>No</option>
</select>

<select name="uv_exposure">
<option value="">UV Exposure</option>
<option>Low</option>
<option>Medium</option>
<option>High</option>
</select>

<select name="treatment">
<option value="">Treatment</option>
<option>Medication</option>
<option>Surgery</option>
<option>Glasses</option>
<option>Observation</option>
</select>

<input type="number" name="treatment_cost" placeholder="Treatment Cost">

<select name="status" required>
<option value="">Outcome</option>
<option>Recovered</option>
<option>Improving</option>
<option>No Change</option>
<option>Worsened</option>
</select>

<input type="date" name="followup">
<input type="text" name="doctor_id" placeholder="Doctor ID">

<select name="visit_type">
<option>New</option>
<option>Follow-up</option>
</select>

<button class="full" type="submit">Submit Patient</button>

</div>

</form>

</div>

</div>

</body>
</html>