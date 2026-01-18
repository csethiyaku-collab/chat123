<?php
include("config/db.php");
$id = $_GET['id'];

$conn->query("UPDATE call_requests SET status='rejected' WHERE id=$id");

header("Location: dashboard.php");
