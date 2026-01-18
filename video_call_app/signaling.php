<?php
include("config/db.php");

if($_POST['type']=="send"){
    $conn->query("INSERT INTO signals(sender,receiver,data) VALUES(
        ".$_POST['sender'].",
        ".$_POST['receiver'].",
        '".$conn->real_escape_string($_POST['data'])."'
    )");
}

if($_GET['type']=="receive"){
    $r = $conn->query("SELECT * FROM signals WHERE receiver=".$_GET['receiver']);
    $out=[];
    while($row=$r->fetch_assoc()){
        $out[]=$row;
        $conn->query("DELETE FROM signals WHERE id=".$row['id']);
    }
    echo json_encode($out);
}
