<?php
session_start();
if(isset($_SESSION["admin"])) header("Location: dashboard.php");

$error = "";
if($_POST){
  if($_POST["user"] === "admin" && $_POST["pass"] === "12345"){
    $_SESSION["admin"] = true;
    header("Location: dashboard.php");
    exit;
  } else {
    $error = "Invalid login";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<style>
body{background:#0b1620;color:#fff;font-family:Arial;display:flex;justify-content:center;align-items:center;height:100vh}
form{background:#111;padding:30px;border-radius:10px;width:300px}
input,button{width:100%;padding:10px;margin:10px 0}
button{background:#ffcc00;border:none;font-weight:bold}
</style>
</head>
<body>
<form method="post">
  <h2>Admin Login</h2>
  <input name="user" placeholder="Username">
  <input name="pass" type="password" placeholder="Password">
  <button>Login</button>
  <div style="color:red"><?= $error ?></div>
</form>
</body>
</html>
