<?php 
include("../config/db.php");
if(!isset($_SESSION['admin'])) die("Access denied");

// Handle assignment
if(isset($_POST['assign'])){
    $u = intval($_POST['user']);
    $f = intval($_POST['friend']);

    if($u == $f){
        $msg = "You cannot assign a user to themselves!";
    } else {
        $conn->query("UPDATE users SET assigned_user=$f WHERE id=$u");
        $conn->query("UPDATE users SET assigned_user=$u WHERE id=$f");
        // Redirect to dashboard after assignment
        header("Location: dashboard.php?msg=Users+assigned+successfully");
        exit;
    }
}

// Fetch users
$users = $conn->query("SELECT * FROM users WHERE approved=1");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Assign Users</title>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
/* Global Styles */
body {
    margin: 0;
    font-family: 'Roboto', sans-serif;
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Card */
.card {
    background: #fff;
    padding: 40px 30px;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    width: 350px;
    text-align: center;
    animation: fadeIn 1s ease-in-out;
}

@keyframes fadeIn {
    from {opacity:0; transform: translateY(-20px);}
    to {opacity:1; transform: translateY(0);}
}

h2 {
    margin-bottom: 20px;
    color: #333;
}

/* Message */
p.msg {
    color: green;
    margin-bottom: 20px;
    font-weight: 500;
}

/* Select Boxes */
select {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 20px;
    border-radius: 10px;
    border: 1px solid #ccc;
    font-size: 16px;
    transition: all 0.3s ease;
}

select:focus {
    outline: none;
    border-color: #2575fc;
    box-shadow: 0 0 5px rgba(37,117,252,0.5);
}

/* Button */
button {
    background: #2575fc;
    color: #fff;
    padding: 12px 25px;
    font-size: 16px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
}

button:hover {
    background: #6a11cb;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Responsive */
@media (max-width: 400px){
    .card {
        width: 90%;
        padding: 30px 20px;
    }
}
</style>
<script>
function confirmAssign() {
    return confirm("Are you sure you want to assign these users?");
}
</script>
</head>
<body>

<form method="post" class="card" onsubmit="return confirmAssign();">
    <h2>Assign Users</h2>
    <p class="msg"><?= htmlspecialchars($msg ?? '') ?></p>

    <select name="user" required>
        <option value="">Select User</option>
        <?php while($u=$users->fetch_assoc()) echo "<option value='{$u['id']}'>{$u['name']}</option>"; ?>
    </select>

    <select name="friend" required>
        <option value="">Select Friend</option>
        <?php 
        $users->data_seek(0);
        while($u=$users->fetch_assoc()) echo "<option value='{$u['id']}'>{$u['name']}</option>"; 
        ?>
    </select>

    <button name="assign"><a href="dashboard.php">Assign</a></button>
</form>
</body>
</html>
