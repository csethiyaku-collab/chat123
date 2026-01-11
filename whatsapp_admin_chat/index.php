<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<title>Mini WhatsApp</title>
<link rel="stylesheet" href="assets/style.css">

<style>
/* Fullscreen gradient animated background */
body, html{
    height:100%;
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    overflow:hidden;
}

body {
    background: linear-gradient(-45deg, #25d366, #128c7e, #075e54, #128c7e);
    background-size: 400% 400%;
    animation: gradientBG 15s ease infinite;
    display:flex;
    justify-content:center;
    align-items:center;
}

@keyframes gradientBG {
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

/* Card */
.card{
    position:relative;
    background: rgba(255,255,255,0.9);
    padding:40px 50px;
    border-radius:20px;
    text-align:center;
    box-shadow:0 15px 40px rgba(0,0,0,0.3);
    z-index:1;
}

.card h2{
    margin-bottom:30px;
    font-size:32px;
    color:#128c7e;
}

/* Buttons */
.card a{
    display:block;
    margin:12px 0;
    padding:12px 25px;
    text-decoration:none;
    border-radius:25px;
    background: linear-gradient(135deg, #25d366, #128c7e);
    color:white;
    font-weight:bold;
    transition:0.3s;
}

.card a:hover{
    transform: scale(1.05);
    box-shadow:0 10px 20px rgba(0,0,0,0.3);
}

/* Responsive */
@media(max-width:480px){
    .card{
        padding:25px 30px;
    }
    .card h2{font-size:24px;}
}
</style>
</head>
<body>

<!-- Floating bubbles -->
<script>
for(let i=0;i<30;i++){
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
<h2>Mini WhatsApp</h2>
<a href="auth/register.php">Register</a>
<a href="auth/login.php">Login</a>
<a href="admin/admin_login.php">Admin Login</a>
</div>

</body>
</html>
