let pc;
const config = {
 iceServers:[
   {urls:"stun:stun.l.google.com:19302"}
 ]
};

async function startCall(){
 pc = new RTCPeerConnection(config);

 const stream = await navigator.mediaDevices.getUserMedia({
   video:true,
   audio:true
 });

 localVideo.srcObject = stream;
 stream.getTracks().forEach(t=>pc.addTrack(t,stream));

 pc.ontrack = e => remoteVideo.srcObject = e.streams[0];

 pc.onicecandidate = e=>{
   if(e.candidate) sendSignal({candidate:e.candidate});
 };

 const offer = await pc.createOffer();
 await pc.setLocalDescription(offer);
 sendSignal({offer});
}

async function sendSignal(data){
 fetch("signaling.php",{
   method:"POST",
   headers:{"Content-Type":"application/x-www-form-urlencoded"},
   body:`type=send&sender=${myId}&receiver=${friendId}&data=${JSON.stringify(data)}`
 });
}

async function receiveSignal(){
 const r = await fetch(`signaling.php?type=receive&receiver=${myId}`);
 const signals = await r.json();

 for(let s of signals){
   const data = JSON.parse(s.data);

   if(data.offer){
     pc = new RTCPeerConnection(config);

     pc.ontrack = e=>remoteVideo.srcObject=e.streams[0];
     pc.onicecandidate = e=>{
       if(e.candidate) sendSignal({candidate:e.candidate});
     };

     const stream = await navigator.mediaDevices.getUserMedia({video:true,audio:true});
     localVideo.srcObject = stream;
     stream.getTracks().forEach(t=>pc.addTrack(t,stream));

     await pc.setRemoteDescription(data.offer);
     const answer = await pc.createAnswer();
     await pc.setLocalDescription(answer);
     sendSignal({answer});
   }

   if(data.answer) await pc.setRemoteDescription(data.answer);
   if(data.candidate) await pc.addIceCandidate(data.candidate);
 }
}
setInterval(receiveSignal,1000);

function endCall(){
 if(pc) pc.close();
}
