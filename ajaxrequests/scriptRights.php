<?php
include("../include/connectMysql.php");
    $user = $_GET['q'];
    $query = $mysqli->query("SELECT rights FROM users WHERE username = '$user'");
    $rights = $query->fetch_array();
    ?>
    <select name="rights" class="form-control">
        <?php
            if($rights['rights'] === "user"){
                ?>
                <option value="user" selected>Tavakasutaja (hetkel)</option>
                <option value="admin">Administraator</option>
                <?php
            }else{
                ?>
                <option value="user">Tavakasutaja</option>
                <option value="admin" selected>Administraator (hetkel)</option>
                <?php
            }
        ?>
    </select>
    <button class="btn btn-danger" id="editrights" name="editrights" style="margin-top:5px;">Muuda õiguseid!</button>
    <?php
?>