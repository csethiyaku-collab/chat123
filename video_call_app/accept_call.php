<?php
include("config/db.php");
$id = $_GET['id'];

$conn->query("UPDATE call_requests SET status='accepted' WHERE id=$id");

$r = $conn->query("SELECT caller FROM call_requests WHERE id=$id");
$caller = $r->fetch_assoc()['caller'];

header("Location: call.php?user=$caller");
