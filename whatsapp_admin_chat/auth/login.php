<?php
session_start();
include("../config/db.php");

$err = "";

if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    $q = $conn->query("SELECT * FROM users WHERE email='$email'");

    if($q->num_rows){
        $u = $q->fetch_assoc();

        if(!$u['approved']){
            $err = "Your account is not approved yet.";
        } elseif(!password_verify($pass, $u['password'])){
            $err = "Incorrect password.";
        } else {
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['email'] = $u['email'];
            header("Location: ../chat/chat.php");
            exit;
        }
    } else {
        $err = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login - Mini WhatsApp</title>
<style>
/* Fullscreen animated gradient background */
body, html {
    height:100%;
    margin:0;
    font-family:'Segoe UI', sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    background: linear-gradient(-45deg, #25d366, #128c7e, #075e54, #128c7e);
    background-size:400% 400%;
    animation: gradientBG 15s ease infinite;
    overflow:hidden;
}

@keyframes gradientBG {
    0%{background-position:0% 50%;}
    50%{background-position:100% 50%;}
    100%{background-position:0% 50%;}
}

/* Floating bubbles effect */
.bubble {
    position:absolute;
    border-radius:50%;
    background:rgba(255,255,255,0.1);
    animation: floatUp linear infinite;
}

@keyframes floatUp {
    0%{transform:translateY(100vh); opacity:0;}
    50%{opacity:0.5;}
    100%{transform:translateY(-50vh); opacity:0;}
}

/* Glassmorphism card */
.card {
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
.card h2 {
    margin-bottom:30px;
    font-size:28px;
    text-shadow:0 2px 5px rgba(0,0,0,0.2);
}

/* Inputs */
.card input {
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

.card input::placeholder {
    color: rgba(255,255,255,0.7);
}

/* Submit button */
.card button {
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

.card button:hover {
    transform:scale(1.05);
    background: rgba(255,255,255,0.35);
    box-shadow:0 8px 20px rgba(0,0,0,0.3);
}

/* Error message */
.msg {
    margin:10px 0;
    font-size:14px;
    color:#ffecb3;
}

/* Link to register */
.card a {
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
    bubble.className='bubble';
    let size = Math.random()*20+10;
    bubble.style.width=size+'px';
    bubble.style.height=size+'px';
    bubble.style.left=Math.random()*100+'vw';
    bubble.style.animationDuration=(5+Math.random()*10)+'s';
    bubble.style.animationDelay=Math.random()*5+'s';
    document.body.appendChild(bubble);
}
</script>

<form method="post" class="card">
<h2>Login</h2>

<?php if($err): ?><p class="msg"><?= htmlspecialchars($err) ?></p><?php endif; ?>

<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>

<button name="login">Login</button>

<p style="margin-top:10px">
Don't have an account? <a href="register.php">Register</a>
</p>
</form>

</body>
</html>
