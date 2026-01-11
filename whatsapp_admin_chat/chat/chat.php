<?php
include("../config/db.php");
if(!isset($_SESSION['user_id'])){ header("Location: ../auth/login.php"); exit; }

$me = $_SESSION['user_id'];
$r = $conn->query("SELECT u2.id,u2.name FROM users u1 
JOIN users u2 ON u1.assigned_user=u2.id WHERE u1.id=$me");
$friend = $r->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
<title>Chat</title>
<link rel="stylesheet" href="../assets/chat.css">
</head>
<body>

<div class="app">

  <div class="sidebar">
    <h3>Chats</h3>
    <div class="chat-item active">
      <?= htmlspecialchars($friend['name']) ?>
    </div>
    <a href="../auth/logout.php" class="logout">Logout</a>
	<a href="call_history.php" class="logout">📞 Call History</a>

  </div>

  <div class="chat-area">
    <div class="topbar">
      <span><?= htmlspecialchars($friend['name']) ?></span>
      <a href="video_call.php">📹</a>
    </div>

    <div id="messages" class="messages"></div>

    <form id="form" class="input-area">
      <input type="text" id="msg" placeholder="Type a message...">
      <button>➤</button>
    </form>
  </div>

</div>

<script>
const box = document.getElementById("messages");
const input = document.getElementById("msg");

function load(){
 fetch("fetch_messages.php")
  .then(r=>r.text())
  .then(d=>{
    box.innerHTML = d;
    box.scrollTop = box.scrollHeight;
  });
}
setInterval(load,1000); load();

form.onsubmit = e=>{
 e.preventDefault();
 if(input.value.trim()=="") return;
 fetch("send_message.php",{method:"POST",body:new URLSearchParams({msg:input.value})});
 input.value="";
};
</script>

</body>
</html>
