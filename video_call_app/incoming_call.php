<?php
include("config/db.php");
$me = $_SESSION['user_id'];

$q = $conn->query("
SELECT c.id,u.name 
FROM call_requests c 
JOIN users u ON u.id=c.caller
WHERE c.receiver=$me AND c.status='ringing'
ORDER BY c.id DESC LIMIT 1
");

if($q->num_rows){
    echo json_encode($q->fetch_assoc());
}else{
    echo json_encode(null);
}
