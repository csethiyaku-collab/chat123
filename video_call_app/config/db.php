<?php
$conn = new mysqli("localhost","root","","video_call");
if($conn->connect_error){
    die("Database error");
}
session_start();
?>
