<?php
session_start();
include("../config/db.php"); // Database connection

$msg = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);

    if(empty($username) || empty($password) || empty($confirm)){
        $msg = "All fields are required!";
    } elseif($password !== $confirm){
        $msg = "Passwords do not match!";
    } else {
        // Check if username exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0){
            $msg = "Username already exists!";
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt2 = $conn->prepare("INSERT INTO users (username,password,assigned_user) VALUES (?,?,0)");
            $stmt2->bind_param("ss", $username, $hashed);
            if($stmt2->execute()){
                $msg = "Registration successful! Wait for admin approval.";
            } else {
                $msg = "Registration failed!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register - Mini WhatsApp</title>
<style>
/* Fullscreen gradient animated background */
body, html{
    height:100%;
    margin:0;
    font-family:'Segoe UI', sans-serif;
    overflow:hidden;
    display:flex;
    justify-content:center;
    align-items:center;
    background: linear-gradient(-45deg, #25d366, #128c7e, #075e54, #128c7e);
    background-size: 400% 400%;
    animation: gradientBG 15s ease infinite;
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
    background:rgba(255,255,255,0.1);
    animation: floatUp linear infinite;
}

@keyframes floatUp{
    0%{transform:translateY(100vh); opacity:0;}
    50%{opacity:0.5;}
    100%{transform:translateY(-50vh); opacity:0;}
}

/* Glassmorphism Card */
.card{
    position:relative;
    width:350px;
    padding:40px 30px;
    border-radius:20px;
    text-align:center;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255,255,255,0.3);
    box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    z-index:1;
    color:#fff;
}

/* Card heading */
.card h2{
    margin-bottom:30px;
    font-size:28px;
    text-shadow:0 2px 5px rgba(0,0,0,0.2);
}

/* Input fields */
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
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
}

/* Message */
.msg{
    margin:10px 0;
    font-size:14px;
    color:#ffecb3;
}

/* Link to login */
.card a{
    display:block;
    margin-top:15px;
    color:#fff;
    text-decoration:underline;
    font-size:14px;
}

/* Responsive */
@media(max-width:480px){
    .card{width:90%; padding:30px 20px;}
    .card h2{font-size:24px;}
}
</style>
</head>
<body>

<!-- Floating bubbles -->
<script>
for(let i=0;i<25;i++){
    let bubble = document.createElement('div');
    bubble.className = 'bubble';
    let size = Math.random()*20 + 10;
    bubble.style.width = size + 'px';
    bubble.style.height = size + 'px';
    bubble.style.left = Math.random()*100 + 'vw';
    bubble.style.animationDuration = (5 + Math.random()*10) + 's';
    bubble.style.animationDelay = Math.random()*5 + 's';
    document.body.appendChild(bubble);
}
</script>

<div class="card">
<h2>Register</h2>
<?php if($msg): ?><div class="msg"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<form method="POST" action="">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirm" placeholder="Confirm Password" required>
    <button type="submit">Register</button>
</form>
<a href="login.php">Already have an account? Login</a>
</div>

</body>
</html>
