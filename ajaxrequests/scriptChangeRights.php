<?php
include("../include/functions.php");
include("../include/connectMysql.php");

$id = $_POST['id'];
$rights = $_POST['rights'];
echo $_POST['id'];
$mysqli->query("UPDATE users SET rights = '$rights' WHERE username = '$id'");
?>