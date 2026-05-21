<!DOCTYPE html>
<html>
<head>
<title>Appointment Confirmed</title>
<style>
body{
    margin:0;
    font-family:'Segoe UI', sans-serif;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: linear-gradient(135deg,#1e2a4a,#4b6cb7);
    color:white;
}
.box{
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(15px);
    padding:40px;
    border-radius:20px;
    text-align:center;
    box-shadow:0 20px 50px rgba(0,0,0,0.4);
}
h2{margin-bottom:10px;}
.btn{
    display:inline-block;
    margin-top:20px;
    padding:12px 25px;
    background:#27ae60;
    color:white;
    border-radius:10px;
    text-decoration:none;
}
.btn:hover{background:#1e8449;}
</style>
</head>

<body>

<div class="box">
    <h2>✅ Appointment Booked</h2>
    <p>Thank you, <b><?php echo $_GET['name']; ?></b></p>
    <p>Your appointment is scheduled on:</p>
    <h3><?php echo $_GET['date']; ?></h3>

    <p>📌 Our team will review and confirm your request.</p>

    <a href="index.html" class="btn">Go Home</a>
</div>

</body>
</html>