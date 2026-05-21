<!DOCTYPE html>
<html>
<head>
<title>Eye Hospital System</title>

<style>
body {
    margin: 0;
    font-family: Arial;
    background: linear-gradient(to right, #2c3e50, #4ca1af);
    color: white;
    text-align: center;
}

.nav {
    padding: 15px;
    background: rgba(0,0,0,0.5);
}

.nav a {
    color: white;
    margin: 0 15px;
    text-decoration: none;
}

.hero {
    margin-top: 100px;
}

button {
    padding: 12px 25px;
    margin: 10px;
    border: none;
    background: #1abc9c;
    color: white;
    border-radius: 8px;
}
</style>
</head>

<body>

<div class="nav">
    <a href="login.html">Login</a>
    <a href="add_patient.php">Add Patient</a>
    <a href="admin.php">Admin Dashboard</a>
</div>

<div class="hero">
    <h1>👁 Eye Hospital Management System</h1>
    <p>Secure • Smart • Blockchain Enabled</p>

    <button onclick="location.href='login.html'">Get Started</button>
</div>

</body>
</html>