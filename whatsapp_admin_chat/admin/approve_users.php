<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['admin'])) die("Access denied");

$approvedMsg = "";
if(isset($_GET['approved'])) $approvedMsg = "User approved successfully!";

// Fetch unapproved users
$res = $conn->query("SELECT * FROM users WHERE approved=0");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard - Approve Users</title>
<style>
/* Fullscreen gradient animated background */
body, html{
    height:100%;
    margin:0;
    font-family:'Segoe UI', sans-serif;
    display:flex;
    justify-content:center;
    align-items:flex-start;
    padding-top:50px;
    background: linear-gradient(-45deg, #ff6b6b, #ff9f43, #feca57, #ff6b6b);
    background-size: 400% 400%;
    animation: gradientBG 15s ease infinite;
    overflow:hidden;
}

@keyframes gradientBG{
    0%{background-position:0% 50%;}
    50%{background-position:100% 50%;}
    100%{background-position:0% 50%;}
}

/* Floating bubbles */
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

/* Glassmorphism container */
.card{
    width:90%;
    max-width:800px;
    padding:30px;
    border-radius:20px;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border:1px solid rgba(255,255,255,0.3);
    box-shadow:0 8px 32px rgba(0,0,0,0.2);
    color:#fff;
    position:relative;
    margin-bottom:20px;
}

/* Heading */
.card h2{
    text-align:center;
    margin-bottom:20px;
}

/* User item style */
.user{
    padding:12px 15px;
    margin:10px 0;
    border-radius:15px;
    background: rgba(255,255,255,0.25);
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.user a{
    padding:6px 12px;
    border-radius:12px;
    background: rgba(0,200,0,0.3);
    text-decoration:none;
    color:#fff;
    font-weight:bold;
    transition:0.3s;
}

.user a:hover{
    background: rgba(0,200,0,0.6);
}

/* Success message */
.success{
    color:#00ff00;
    text-align:center;
    margin-bottom:15px;
    font-weight:bold;
}

/* Navigation links */
.nav-links{
    text-align:center;
    margin-top:20px;
}

.nav-links a{
    padding:8px 16px;
    border-radius:20px;
    text-decoration:none;
    margin:0 5px;
    background: rgba(255,255,255,0.25);
    color:#fff;
    transition:0.3s;
}

.nav-links a:hover{
    background: rgba(255,255,255,0.35);
}

/* Responsive */
@media(max-width:480px){
    .user{flex-direction:column; gap:10px; text-align:center;}
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

<div class="card">
    <h2>Approve Users</h2>
    <?php if($approvedMsg): ?>
        <div class="success"><?= htmlspecialchars($approvedMsg) ?></div>
    <?php endif; ?>

    <?php while($u = $res->fetch_assoc()): ?>
        <div class="user">
            <span><?= htmlspecialchars($u['name']) ?> - <?= htmlspecialchars($u['email']) ?></span>
            <a href="approve_user_action.php?id=<?= $u['id'] ?>">Approve</a>
        </div>
    <?php endwhile; ?>

    <div class="nav-links">
        <a href="assign_contact.php">Assign Contacts</a>
        <a href="../auth/logout.php">Logout</a>
    </div>
</div>

</body>
</html>
