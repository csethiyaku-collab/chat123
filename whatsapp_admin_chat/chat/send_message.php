<?php
include("../config/db.php");

if(!isset($_SESSION['user_id'])) exit;

$me = $_SESSION['user_id'];
$msg = trim($_POST['msg']);

$r = $conn->query("SELECT assigned_user FROM users WHERE id=$me");
$to = $r->fetch_assoc()['assigned_user'];

if($msg != ""){
  $stmt = $conn->prepare("INSERT INTO messages(sender_id,receiver_id,message) VALUES(?,?,?)");
  $stmt->bind_param("iis", $me, $to, $msg);
  $stmt->execute();
}
