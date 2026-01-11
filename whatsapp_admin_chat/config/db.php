<?php
// Start session only if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Your database connection code
$host = "localhost";
$user = "root";
$password = "";
$dbname = "whatsapp_clone";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
