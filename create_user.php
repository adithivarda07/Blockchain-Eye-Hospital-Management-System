<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "db.php";

$message = "";

if(isset($_POST['create'])){

    $name = $_POST['name'];
    $password_raw = $_POST['password'];
    $role = $_POST['role'];
    $email = $_POST['email'] ?? NULL;

    if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@#$%^&*]).{8,}$/", $password_raw)) {
        $message = "❌ Weak password! Use strong password.";
    } 
    elseif(($role == "admin" || $role == "doctor") && empty($email)){
        $message = "❌ Email is required for Admin/Doctor";
    }
    else {

        $password = password_hash($password_raw, PASSWORD_DEFAULT);

        $short = strtoupper(substr(preg_replace("/[^a-zA-Z]/", "", $name), 0, 4));
        $rand = rand(100,999);

        if($role == "doctor"){
            $username = "DR_" . $short . "_" . $rand;
        } else {
            $username = "ADMIN_" . $rand;
        }

        $sql = "INSERT INTO users(username,password,role,email) 
                VALUES('$username','$password','$role','$email')";

        if($conn->query($sql)){
            $message = "✅ SUCCESS! Username: " . $username;
        } else {
            $message = "❌ ERROR: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Create User</title>

<style>
body {
    font-family: Arial;
    background: linear-gradient(to right, #2c3e50, #4b6cb7);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color: white;
}

.box {
    background: rgba(255,255,255,0.1);
    padding: 30px;
    border-radius: 12px;
    width: 320px;
    text-align: center;
}

input, select {
    padding: 10px;
    margin: 10px 0;
    width: 100%;
    border-radius: 6px;
    border: none;
}

/* PASSWORD BOX */
.password-box {
    position: relative;
}

.password-box input {
    width: 100%;
}

/* SHOW/HIDE TEXT */
.toggle-text {
    position: absolute;
    right: 10px;
    top: 10px;
    cursor: pointer;
    font-size: 13px;
    color: black;
}

button {
    padding: 10px;
    width: 100%;
    background: #27ae60;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    background: #219150;
}
</style>

<script>
function validatePassword(){
    let pass = document.getElementById("pass").value;
    let pattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@#$%^&*]).{8,}$/;

    if(!pattern.test(pass)){
        alert("Password must contain:\n- 8 characters\n- Uppercase\n- Lowercase\n- Number\n- Special symbol");
        return false;
    }
    return true;
}

function togglePassword(){
    let p = document.getElementById("pass");
    let text = document.getElementById("toggleText");

    if(p.type === "password"){
        p.type = "text";
        text.innerHTML = "Hide";
    } else {
        p.type = "password";
        text.innerHTML = "Show";
    }
}

function toggleEmail(){
    let role = document.getElementById("role").value;
    let email = document.getElementById("email");

    if(role === "admin" || role === "doctor"){
        email.style.display = "block";
        email.required = true;
    } else {
        email.style.display = "none";
        email.required = false;
        email.value = "";
    }
}
window.onload = function() {
    toggleEmail();
};

</script>

</head>

<body>

<div class="box">
<a href="admin.php">
    <button style="background:#e74c3c; margin-top:10px;">⬅ Back</button>
</a>
<h2>Create Doctor/Admin</h2>

<form method="POST" onsubmit="return validatePassword()">

<input type="text" name="name" placeholder="Name" required>

<div class="password-box">
    <input type="password" id="pass" name="password" placeholder="Password" required>
    <span id="toggleText" class="toggle-text" onclick="togglePassword()">Show</span>
</div>

<select name="role" id="role" onchange="toggleEmail()">
<option value="doctor">Doctor</option>
<option value="admin">Admin</option>
</select>

<!-- ✅ FIXED EMAIL FIELD -->
<input type="email" id="email" name="email" placeholder="Enter Email" style="display:none;">

<button name="create">Create</button>

</form>

<p><?php echo $message; ?></p>

</div>

</body>
</html>