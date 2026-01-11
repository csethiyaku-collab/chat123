<?php
include("../config/db.php");

if(!isset($_SESSION['user_id'])) exit;

$me = $_SESSION['user_id'];

$r = $conn->query("SELECT assigned_user FROM users WHERE id=$me");
$f = $r->fetch_assoc()['assigned_user'];

$res = $conn->query("
  SELECT * FROM messages
  WHERE (sender_id=$me AND receiver_id=$f) OR (sender_id=$f AND receiver_id=$me)
  ORDER BY id ASC
");

while($m = $res->fetch_assoc()){
  $class = $m['sender_id'] == $me ? 'me' : 'other';
  echo "<div class='msg $class'>".htmlspecialchars($m['message'])."</div>";
}
