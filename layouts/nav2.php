<div class="col-3">
    <div class="menu">
        <div class="panel-header text-center"><h3>Menüü</h3></div>
        <ul>
            <li><a href="home">Kodu</a></li>
            <?php
            $rights = getRights($_SESSION['username'], $mysqli);
            if($rights == "admin"){
                ?>
                <li><a href="admin">Admini paneel</a></li>
                <?php
            }
            ?>
            <li><a href="logout">Logi välja</a></li>
        </ul>
    </div>
</div>