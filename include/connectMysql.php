<?php
include_once('config.php');
$mysqli = new mysqli(SERVER,USER,PASSWORD,DATABASE);

// Check connection
if ($mysqli->connect_error)
{
    echo "Ei suutnud MySql andmebaasiga ühendust luua: " . $mysqli->connect_error;
}
?>