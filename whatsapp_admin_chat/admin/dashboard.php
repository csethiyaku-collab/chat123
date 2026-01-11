<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['admin'])) die("Access denied");

// Handle approve/reject/delete actions
if(isset($_GET['approve'])){
    $id = intval($_GET['approve']);
    $conn->query("UPDATE users SET approved=1 WHERE id=$id");
    header("Location: dashboard.php?msg=User+approved");
    exit;
}

if(isset($_GET['reject'])){
    $id = intval($_GET['reject']);
    $conn->query("UPDATE users SET approved=0 WHERE id=$id");
    header("Location: dashboard.php?msg=User+rejected");
    exit;
}

if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: dashboard.php?msg=User+deleted");
    exit;
}

// Fetch all users
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Poppins',sans-serif;background:#0f2027;color:#fff;overflow-x:hidden;}

/* Animated gradient background */
body::before{
    content:"";position:fixed;top:0;left:0;width:100%;height:100%;
    background: linear-gradient(-45deg,#0f2027,#203a43,#2c5364,#11998e);
    background-size:400% 400%;animation: gradientBG 20s ease infinite; z-index:-1;
}
@keyframes gradientBG{0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}

/* Sidebar */
.sidebar{
    position:fixed; top:0; left:0; height:100%; width:220px;
    background: rgba(0,0,0,0.5); backdrop-filter:blur(10px);
    border-right:1px solid rgba(255,255,255,0.2); padding-top:60px;
}
.sidebar h2{text-align:center; margin-bottom:30px; color:#0ff; text-shadow:0 0 10px #0ff;}
.sidebar a{display:block; padding:12px 20px; margin:10px; color:#fff; text-decoration:none; border-radius:10px; transition:0.3s; font-weight:600;}
.sidebar a:hover{background:#0ff;color:#000;box-shadow:0 0 10px #0ff;}

/* Main content */
.main{margin-left:220px; padding:60px 40px;}
.main h1{font-size:28px; margin-bottom:20px; color:#0ff; text-shadow:0 0 8px #0ff;}

/* Success message */
.msg{padding:10px 15px; background: rgba(0,255,128,0.2); color:#0f0; border-radius:8px; text-align:center; margin-bottom:20px;}

/* User panel */
.user-panel{display:flex; flex-wrap:wrap; gap:20px;}
.user-card{
    flex:1 1 250px; background: rgba(255,255,255,0.05);
    border-left:5px solid #0ff; border-radius:15px;
    padding:20px; position:relative; overflow:hidden;
    transition:0.3s; box-shadow:0 0 10px rgba(0,255,255,0.2);
}
.user-card:hover{transform:translateY(-5px); box-shadow:0 0 25px rgba(0,255,255,0.5);}
.user-card h3{margin-bottom:10px; font-size:18px;}
.user-card p{margin-bottom:10px; font-size:14px; color:#ccc;}
.user-card .status{position:absolute; top:20px; right:20px; padding:4px 10px; border-radius:12px; font-size:12px; font-weight:600; color:#000; background:#0f0;}
.user-card .status.pending{background:#ff9800;}
.user-card .actions{margin-top:15px; display:flex; justify-content:space-between;}
.user-card .actions a{flex:1; text-align:center; padding:8px 0; border-radius:8px; text-decoration:none; font-weight:600; margin:0 5px; transition:0.3s;}
.user-card .actions a.approve{background:#0f0; color:#000;}
.user-card .actions a.approve:hover{background:#00ff00; box-shadow:0 0 8px #0f0;}
.user-card .actions a.reject{background:#ff9800; color:#000;}
.user-card .actions a.reject:hover{background:#ffb84d; box-shadow:0 0 8px #ff9800;}
.user-card .actions a.delete{background:#f00; color:#fff;}
.user-card .actions a.delete:hover{background:#ff4c4c; box-shadow:0 0 8px #f00;}

/* Responsive */
@media(max-width:768px){.main{padding:60px 20px;}}
@media(max-width:480px){.user-panel{flex-direction:column;}}
</style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="assign_contact.php">Assign Contacts</a>
    <a href="../auth/logout.php">Logout</a>
</div>

<div class="main">
    <h1>User Approvals</h1>

    <?php if($msg): ?>
        <div class="msg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <div class="user-panel">
        <?php while($user = $users->fetch_assoc()): ?>
        <div class="user-card">
            <h3><?= htmlspecialchars($user['name']) ?></h3>
            <p>Email: <?= htmlspecialchars($user['email']) ?></p>
            <div class="status <?= $user['approved'] ? '' : 'pending' ?>"><?= $user['approved'] ? 'Approved' : 'Pending' ?></div>
            <div class="actions">
                <?php if(!$user['approved']): ?>
                    <a class="approve" href="?approve=<?= $user['id'] ?>">Approve</a>
                <?php else: ?>
                    <a class="reject" href="?reject=<?= $user['id'] ?>">Reject</a>
                <?php endif; ?>
                <a class="delete" href="?delete=<?= $user['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
