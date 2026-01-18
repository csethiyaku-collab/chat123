<?php
include("config/db.php");
$receiver = $_GET['user'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Calling...</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
body{
    background:#000;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    height:100vh;
    font-family:Arial;
}
.box{
    text-align:center;
}
.loader{
    margin:20px auto;
    width:50px;
    height:50px;
    border:5px solid #333;
    border-top:5px solid #00c853;
    border-radius:50%;
    animation:spin 1s linear infinite;
}
@keyframes spin{
    100%{transform:rotate(360deg)}
}
</style>
</head>
<body>

<div class="box">
    <h2>📞 Calling...</h2>
    <div class="loader"></div>
    <p>Waiting for answer</p>
</div>

<script>
setInterval(async ()=>{
    const r = await fetch("check_call.php?user=<?=$receiver?>");
    const res = await r.text();

    if(res=="accepted"){
        window.location.href="call.php?user=<?=$receiver?>";
    }
    if(res=="rejected"){
        alert("Call rejected");
        window.location.href="dashboard.php";
    }
},1500);
</script>

</body>
</html>
