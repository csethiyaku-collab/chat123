<?php
include("../config/db.php");
$me=$_SESSION['user_id'];
$r=$conn->query("SELECT assigned_user FROM users WHERE id=$me")->fetch_assoc();
$to=$r['assigned_user'];
$a=$_POST['action'];

if($a=="ring") $conn->query("INSERT INTO call_requests(from_id,to_id,status) VALUES($me,$to,'ringing')");
if($a=="accept") $conn->query("UPDATE call_requests SET status='accepted' WHERE to_id=$me");
if($a=="reject") $conn->query("UPDATE call_requests SET status='rejected' WHERE to_id=$me");
if($a=="end") $conn->query("UPDATE call_requests SET status='ended' WHERE from_id=$me OR to_id=$me");
