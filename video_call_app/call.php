<?php
include("config/db.php");
$me = $_SESSION['user_id'];
$friend = $_GET['user'];
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="videos">
    <video id="localVideo" autoplay muted playsinline></video>
    <video id="remoteVideo" autoplay playsinline></video>
</div>

<div class="controls">
    <button onclick="startCall()">Call</button>
    <button onclick="endCall()">End</button>
</div>

<script>
const myId = <?=$me?>;
const friendId = <?=$friend?>;
</script>
<script src="assets/js/webrtc.js"></script>
</body>
</html>
