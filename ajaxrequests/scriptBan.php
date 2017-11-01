<?php
include("../include/functions.php");
include("../include/connectMysql.php");

$id = $_POST['id'];
$ban = $mysqli->query("SELECT ban FROM users WHERE ID = '$id'");
$row = $ban->fetch_array();
if($ban == 0) {
    $mysqli->query("UPDATE users SET ban = '1' WHERE ID = '$id'");
}else{
    $mysqli->query("UPDATE users SET ban = '0' WHERE ID = '$id'");
}
?>