<?php
include("../include/connectMysql.php");
    $user = $_GET['q'];
    $query = $mysqli->query("SELECT ban FROM users WHERE username = '$user'");
    $ban = $query->fetch_array();
    if($ban['ban'] == 0){
        ?>
        <button class="btn btn-danger" id="ban" name="ban" style="margin-top:5px;">Pane kasutajale mängukeeld!</button>
        <?php
    }else{
        ?>
        <button class="btn btn-danger" id="unban" name="unban" style="margin-top:5px;">Võta kasutajalt mängukeeld ära!</button>
        <?php
    }
?>