<?php
include("config/db.php");
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$me = $_SESSION['user_id'];
$users = $conn->query("SELECT * FROM users WHERE id != $me");
?>
<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: Arial, Helvetica, sans-serif;
}
body{
    min-height:100vh;
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    color:#fff;
    display:flex;
    justify-content:center;
    align-items:center;
}
.container{
    width:95%;
    max-width:420px;
    background:#111;
    padding:20px;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,.6);
}
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:15px;
}
.header h2{
    font-size:22px;
}
.logout{
    color:#ff5252;
    text-decoration:none;
    font-size:14px;
}
.user-list{
    margin-top:10px;
}
.user{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:12px;
    background:#1c1c1c;
    border-radius:8px;
    margin-bottom:10px;
}
.user-name{
    font-size:16px;
}
.status{
    font-size:12px;
    opacity:.8;
}
.call-btn{
    background:#00c853;
    color:#000;
    border:none;
    padding:8px 14px;
    border-radius:6px;
    font-weight:bold;
    cursor:pointer;
    text-decoration:none;
}
.call-btn:hover{
    opacity:.85;
}
.empty{
    text-align:center;
    opacity:.7;
    margin-top:20px;
}
</style>
</head>
<body>
<script>
setInterval(async ()=>{
    const r = await fetch("incoming_call.php");
    const data = await r.json();

    if(data && data.caller){
        if(confirm("📞 Incoming call from " + data.name)){
            location.href = "accept_call.php?id=" + data.id;
        }else{
            location.href = "reject_call.php?id=" + data.id;
        }
    }
},2000);
</script>


<div class="container">
    <div class="header">
        <h2>📞 Dashboard</h2>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <p style="font-size:14px;opacity:.8;margin-bottom:10px;">
        Select a user to start video call
    </p>

    <div class="user-list">
        <?php if($users->num_rows){ ?>
            <?php while($u = $users->fetch_assoc()){ ?>
                <div class="user">
                    <div>
                        <div class="user-name"><?=htmlspecialchars($u['name'])?></div>
                        <div class="status">Status: <?=$u['status']?></div>
                    </div>
                    <a href="call_request.php?user=<?=$u['id']?>" class="call-btn">
    Call
</a>

                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="empty">No users available</div>
        <?php } ?>
    </div>
</div>

</body>
</html>
