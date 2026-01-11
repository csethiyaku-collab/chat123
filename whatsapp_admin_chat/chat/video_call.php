<?php
include("../config/db.php");
if(!isset($_SESSION['user_id'])){ header("Location: ../auth/login.php"); exit; }

$me=$_SESSION['user_id'];
$r=$conn->query("SELECT u2.id,u2.name FROM users u1 JOIN users u2 ON u1.assigned_user=u2.id WHERE u1.id=$me");
$friend=$r->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
<title>Video Call</title>
<style>
body{background:#111;color:white;text-align:center;font-family:Arial;margin:0;padding:0}
.video-container{position:relative;width:100%;height:70vh;background:black}
video{width:100%;height:100%;border-radius:8px;object-fit:cover}
video.small{position:absolute;width:25%;height:25%;bottom:10px;right:10px;border:2px solid white;border-radius:8px}
.controls{display:flex;justify-content:center;flex-wrap:wrap;margin:10px}
.controls button{margin:5px;padding:10px 15px;border:none;border-radius:6px;background:#25d366;color:white;font-size:16px;cursor:pointer}
.controls button.red{background:#e53935}
#ringBox{position:fixed;top:30%;left:50%;transform:translateX(-50%);
 background:#222;padding:20px;border-radius:10px;display:none}
#chatSidebar{position:absolute;top:0;right:0;width:300px;height:100%;background:#222;color:white;padding:10px;overflow-y:auto;display:none}
#chatBox{height:80%;overflow-y:auto;margin-bottom:5px}
#chatInput{width:100%;padding:8px;border-radius:6px;border:none}
#timer{margin:5px;font-size:16px}
</style>
</head>
<body>

<h2 id="statusText">Ready</h2>
<div id="timer">00:00</div>

<div class="video-container">
  <video id="remoteVideo" autoplay></video>
  <video id="localVideo" autoplay muted class="small"></video>
</div>

<div class="controls">
  <button onclick="startCall()">📞 Call</button>
  <button onclick="toggleMic()">🎤 Mic</button>
  <button onclick="toggleCam()">📷 Camera</button>
  <button onclick="shareScreen()">💻 Screen</button>
  <button onclick="toggleFullscreen()">📏 Fullscreen</button>
  <button onclick="openChat()">💬 Chat</button>
  <button class="red" onclick="endCall()">❌ End</button>
</div>

<div id="ringBox">
  <p id="ringText"></p>
  <button class="green" onclick="acceptCall()">Accept</button>
  <button class="red" onclick="rejectCall()">Reject</button>
</div>

<div id="chatSidebar">
  <div id="chatBox"></div>
  <input id="chatInput" placeholder="Type a message..." />
</div>

<audio id="ringtone" src="../assets/ringtone.mp3" loop></audio>

<script>
let pc, localStream, callTimer, seconds=0;
const me = <?= $me ?>;
const friend = <?= $friend['id'] ?>;
const ringtone = document.getElementById("ringtone");
const chatSidebar = document.getElementById("chatSidebar");
const chatBox = document.getElementById("chatBox");
const chatInput = document.getElementById("chatInput");
const cfg = {iceServers:[{urls:'stun:stun.l.google.com:19302'}]};

// Timer
function updateTimer(){ seconds++;
 let m = String(Math.floor(seconds/60)).padStart(2,'0');
 let s = String(seconds%60).padStart(2,'0');
 document.getElementById("timer").textContent = m+":"+s;
}

// Chat send
chatInput.addEventListener("keypress", function(e){
 if(e.key==='Enter' && chatInput.value.trim()!==''){
   fetch("send_message.php",{method:"POST",body:new URLSearchParams({msg:chatInput.value})});
   chatInput.value="";
 }
});

function loadChat(){
 fetch("fetch_messages.php").then(r=>r.text()).then(d=>{ chatBox.innerHTML=d; chatBox.scrollTop=chatBox.scrollHeight; });
}
setInterval(loadChat,1000); loadChat();

async function startCall(){
 fetch("call_request.php",{method:"POST",body:new URLSearchParams({action:"ring"})});
 document.getElementById("statusText").textContent="Calling...";
}

// Accept / Reject
async function acceptCall(){ ringBox.style.display="none";
 fetch("call_request.php",{method:"POST",body:new URLSearchParams({action:"accept"})});
 await startWebRTC(true);
}
function rejectCall(){ ringBox.style.display="none";
 fetch("call_request.php",{method:"POST",body:new URLSearchParams({action:"reject"})});
 document.getElementById("statusText").textContent="Rejected"; }

// WebRTC
async function startWebRTC(isAnswer=false){
 localStream = await navigator.mediaDevices.getUserMedia({video:true,audio:true});
 document.getElementById("localVideo").srcObject = localStream;

 pc = new RTCPeerConnection(cfg);
 localStream.getTracks().forEach(t=>pc.addTrack(t,localStream));
 pc.ontrack = e=>document.getElementById("remoteVideo").srcObject=e.streams[0];
 pc.onicecandidate = e=>e.candidate && sendSignal("ice",e.candidate);

 if(!isAnswer){
  const offer = await pc.createOffer();
  await pc.setLocalDescription(offer);
  sendSignal("offer",offer);
 }

 callTimer = setInterval(updateTimer,1000);
 document.getElementById("statusText").textContent="In Call";
}

// Signaling
async function handleSignal(s){
 if(!s) return;
 if(s.type=="offer"){ await startWebRTC(true); await pc.setRemoteDescription(s.data);
  const ans = await pc.createAnswer(); await pc.setLocalDescription(ans); sendSignal("answer",ans);}
 if(s.type=="answer") await pc.setRemoteDescription(s.data);
 if(s.type=="ice") await pc.addIceCandidate(s.data);
}
function sendSignal(type,data){ fetch("signaling.php",{method:"POST",body:new URLSearchParams({type,data:JSON.stringify(data)})}); }

// Toggle Mic / Cam
function toggleMic(){ localStream.getAudioTracks()[0].enabled = !localStream.getAudioTracks()[0].enabled; }
function toggleCam(){ localStream.getVideoTracks()[0].enabled = !localStream.getVideoTracks()[0].enabled; }

// Screen share
async function shareScreen(){
 try{
  const screenStream = await navigator.mediaDevices.getDisplayMedia({video:true});
  const sender = pc.getSenders().find(s=>s.track.kind==='video');
  sender.replaceTrack(screenStream.getTracks()[0]);
  screenStream.getTracks()[0].onended = ()=>sender.replaceTrack(localStream.getVideoTracks()[0]);
 }catch(e){ console.log("Screen share failed",e);}
}

// Fullscreen
function toggleFullscreen(){
 if(!document.fullscreenElement){ document.documentElement.requestFullscreen(); } 
 else{ document.exitFullscreen(); }
}

// Chat sidebar
function openChat(){ chatSidebar.style.display = chatSidebar.style.display==='none'?'block':'none'; }

// End call
function endCall(){ fetch("call_request.php",{method:"POST",body:new URLSearchParams({action:"end"})}); location.reload(); }

// Polling: signals + incoming calls
setInterval(()=>{
 fetch("fetch_signal.php").then(r=>r.json()).then(arr=>arr.forEach(handleSignal));
 fetch("fetch_call.php").then(r=>r.json()).then(c=>{
   if(c && c.status=="ringing" && c.to_id==me){ ringtone.play(); ringBox.style.display="block"; ringText.textContent="Incoming call..."; }
   if(c && c.status=="accepted"){ ringtone.pause(); ringtone.currentTime=0; startWebRTC(false);}
   if(c && (c.status=="rejected" || c.status=="ended")){ ringtone.pause(); ringtone.currentTime=0; document.getElementById("statusText").textContent=c.status; clearInterval(callTimer);}
 });
},1000);
</script>

</body>
</html>
