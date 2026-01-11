<?php
include("../config/db.php");
$me=$_SESSION['user_id'];

$r=$conn->query("SELECT * FROM call_requests WHERE to_id=$me OR from_id=$me ORDER BY id DESC LIMIT 1");
$c=$r->fetch_assoc();

if($c && $c['status']=='ringing' && strtotime($c['created_at']) < time()-30){
 $conn->query("UPDATE call_requests SET status='ended',missed=1,ended_at=NOW() WHERE id=".$c['id']);
}

echo json_encode($c);
