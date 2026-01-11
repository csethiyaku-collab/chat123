<?php include("../config/db.php");

if(isset($_POST['login'])){
  $u = $_POST['username'];
  $p = md5($_POST['password']);
  $q = $conn->query("SELECT * FROM admin WHERE username='$u' AND password='$p'");
  if($q->num_rows){
    $_SESSION['admin'] = $u;
    header("Location: dashboard.php");
  } else $err = "Invalid admin login";
}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../assets/style.css">
<title>Admin Login</title>
</head>
<body class="center">
<style>
/* Fullscreen gradient animated background */
body, html{
    height:100%;
    margin:0;
    font-family:'Segoe UI', sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    background: linear-gradient(-45deg, #ff6b6b, #ff9f43, #feca57, #ff6b6b);
    background-size:400% 400%;
    animation: gradientBG 15s ease infinite;
    overflow:hidden;
}

@keyframes gradientBG{
    0%{background-position:0% 50%;}
    50%{background-position:100% 50%;}
    100%{background-position:0% 50%;}
}

/* Floating bubbles effect */
.bubble{
    position:absolute;
    border-radius:50%;
    background: rgba(255,255,255,0.1);
    animation: floatUp linear infinite;
}

@keyframes floatUp{
    0%{transform:translateY(100vh); opacity:0;}
    50%{opacity:0.5;}
    100%{transform:translateY(-50vh); opacity:0;}
}

/* Glassmorphism Card */
.card{
    width:350px;
    padding:40px 30px;
    border-radius:20px;
    text-align:center;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border:1px solid rgba(255,255,255,0.3);
    box-shadow:0 8px 32px rgba(0,0,0,0.2);
    color:#fff;
    z-index:1;
    position:relative;
}

/* Heading */
.card h2{
    margin-bottom:30px;
    font-size:28px;
    text-shadow:0 2px 5px rgba(0,0,0,0.2);
}

/* Inputs */
.card input{
    width:100%;
    padding:12px 15px;
    margin:8px 0;
    border-radius:20px;
    border:none;
    outline:none;
    background: rgba(255,255,255,0.25);
    color:#fff;
    font-size:14px;
}

.card input::placeholder{
    color: rgba(255,255,255,0.7);
}

/* Submit button */
.card button{
    width:100%;
    padding:12px;
    margin-top:15px;
    border:none;
    border-radius:20px;
    background: rgba(255,255,255,0.25);
    color:#fff;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
    backdrop-filter: blur(5px);
}

.card button:hover{
    transform:scale(1.05);
    background: rgba(255,255,255,0.35);
    box-shadow:0 8px 20px rgba(0,0,0,0.3);
}

/* Error message */
.msg{
    margin:10px 0;
    font-size:14px;
    color:#ffecb3;
}

/* Responsive */
@media(max-width:480px){
    .card{width:90%; padding:30px 20px;}
    .card h2{font-size:24px;}
}
</style>
<form method="post" class="card">
<h2>Admin Login</h2>
<p style="color:red"><?= $err ?? '' ?></p>
<input name="username" placeholder="Username" required>
<input name="password" type="password" placeholder="Password" required>
<button name="login">Login</button>
</form>
</body>
</html>
