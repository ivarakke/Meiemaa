<?php
include("../include/functions.php");
include("../include/connectMysql.php");

$id = $_POST['id'];
echo $_POST['id'];
$ban = $mysqli->query("SELECT ban FROM users WHERE username = '$id'");
$row = $ban->fetch_array();
if($row['ban'] == 0) {
    $mysqli->query("UPDATE users SET ban = '1' WHERE username = '$id'");
}else{
    $mysqli->query("UPDATE users SET ban = '0' WHERE username = '$id'");
}
?>