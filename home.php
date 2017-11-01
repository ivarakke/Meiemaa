
<?php

include("layouts/header.php");
include("include/functions.php");
session_start();
include("include/connectMysql.php");
if(!login_check($mysqli)){
    redirectTo("login.php");
}else {
    $ban = getBan($_SESSION['user_id']);
    if($ban['ban'] != 0){
        redirectTo("login");
    }
    ?>

    <div class="row">
        <?php
        require('layouts/nav2.php');
        ?>
        <div class="col-6">
            <div class="content">
                <h3>Siia tuleb mäng</h3>
            </div>
        </div>
        <?php
        require('layouts/data.php');
        ?>
    </div>


    <?php
}
include("layouts/footer.php");
?>
