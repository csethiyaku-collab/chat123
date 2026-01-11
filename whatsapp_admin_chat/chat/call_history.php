<?php
include("../config/db.php");
if(!isset($_SESSION['user_id'])){ header("Location: ../auth/login.php"); exit; }

$me=$_SESSION['user_id'];

$res=$conn->query("
 SELECT c.*, u.name FROM call_requests c
 JOIN users u ON IF(c.from_id=$me,c.to_id,c.from_id)=u.id
 WHERE c.from_id=$me OR c.to_id=$me
 ORDER BY c.id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Call History</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body class="center">
<div class="card" style="max-width:500px;width:100%">
<h2>Call History</h2>
<table width="100%">
<tr><th>User</th><th>Status</th><th>Time</th></tr>
<?php while($c=$res->fetch_assoc()){ ?>
<tr>
<td><?= htmlspecialchars($c['name']) ?></td>
<td><?= $c['missed']?'Missed':ucfirst($c['status']) ?></td>
<td><?= $c['created_at'] ?></td>
</tr>
<?php } ?>
</table>
<a href="chat.php">← Back</a>
</div>
</body>
</html>
