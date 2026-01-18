<?php
include("config/db.php");

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $q = $conn->query("SELECT * FROM users WHERE email='$email'");
    if($q->num_rows){
        $u = $q->fetch_assoc();
        if(password_verify($pass,$u['password'])){
            $_SESSION['user_id'] = $u['id'];
            $conn->query("UPDATE users SET status='online' WHERE id=".$u['id']);
            header("Location: dashboard.php");
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>

<div class="container">
    <h1>Welcome Back</h1>
    <p>Login to continue video call</p>

    <form method="post">
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="login">Login</button>
    </form>

    <div class="link">
        New user? <a href="register.php">Create account</a>
    </div>
</div>

</body>
</html>
