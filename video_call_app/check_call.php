<?php
include("config/db.php");
$caller = $_SESSION['user_id'];
$receiver = $_GET['user'];

$q = $conn->query("SELECT status FROM call_requests 
                   WHERE caller=$caller AND receiver=$receiver");

if($q->num_rows){
    echo $q->fetch_assoc()['status'];
}
