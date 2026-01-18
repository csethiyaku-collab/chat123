<?php
include("config/db.php");
if(!isset($_SESSION['user_id'])) exit;

$caller = $_SESSION['user_id'];
$receiver = $_GET['user'];

// remove old requests
$conn->query("DELETE FROM call_requests 
              WHERE caller=$caller AND receiver=$receiver");

// insert new call
$conn->query("INSERT INTO call_requests(caller,receiver) 
              VALUES($caller,$receiver)");

header("Location: calling.php?user=$receiver");
