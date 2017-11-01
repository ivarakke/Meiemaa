
<?php

include("layouts/header.php");
include("include/functions.php");
session_start();
include("include/connectMysql.php");
if(!login_check($mysqli)){
    redirectTo("login");
}else {
    $ban = getBan($_SESSION['user_id']);
    if($ban['ban'] != 0){
        redirectTo("login");
    }
    ?>

    <div class="row">
        <?php
        require('layouts/navadmin.php');
        ?>
        <div class="col-6">
            <div class="content">
                <?php
                    if(!isset($_GET['action'])) {
                        ?>
                        <h3>Admini paneel</h3>
                        <?php
                    }else{
                        if($_GET['action'] === "ban"){
                            ?>
                            <h3>Mängukeelud</h3>
                            <?php
                                $users = getUsers($mysqli);

                                if(isset($_POST['ban'])){
                                    ?>
                                    <script>
                                        swal({
                                            title: 'Kas oled kindel?',
                                            text: 'Sul peab olema väga hea põhjus sellele kasutajale mängukeelu panemiseks.',
                                            type: 'warning',
                                            showCancelButton: true,
                                            confirmButtonText: 'Jah!',
                                            cancelButtonText: 'Katkesta!'
                                        }).then(function () {
                                            $.ajax({
                                                url: "ajaxrequests/scriptBan.php",
                                                type: "POST",
                                                dataType: "html",
                                                data: 'id=<?php echo $_POST['users']; ?>',
                                                success: function () {
                                                    swal("Valmis!", "Kasutaja edukalt mängukeeld peale pandud!", "success")
                                                },
                                                error: function (xhr, ajaxOptions, thrownError) {
                                                    swal("Katkestatud!", "Kasutajale ei pandud mängukeeldu!", "error")
                                                }
                                            });
                                        });
                                    </script>
                                    <?php
                                }

                                    if(isset($_POST['unban'])){
                                    ?>
                                    <script>
                                        swal({
                                            title: 'Kas oled kindel?',
                                            text: 'See kasutaja võib siiski veel ohtlik olla.',
                                            type: 'warning',
                                            showCancelButton: true,
                                            confirmButtonText: 'Jah!',
                                            cancelButtonText: 'Katkesta!'
                                        }).then(function () {
                                            $.ajax({
                                                url: "ajaxrequests/scriptBan.php",
                                                type: "POST",
                                                dataType: "html",
                                                data: 'id=<?php echo $_POST['users']; ?>',
                                                success: function () {
                                                    swal("Valmis!", "Kasutajalt edukalt mängukeeld maha võetud!", "success")
                                                },
                                                error: function (xhr, ajaxOptions, thrownError) {
                                                    swal("Katkestatud!", "Kasutaja mängukeeldu ei võetud maha!", "error")
                                                }
                                            });
                                        });
                                    </script>
                                <?php
                                }

                            ?>

                            <form method="POST" action="">
                                <select name="users" id="usersSelect" class='form-control'>
                                    <option>Vali kasutaja...</option>
                                    <?php
                                    while($row = $users->fetch_array(MYSQLI_ASSOC)){
                                        echo "<option value='$row[username]' >$row[username]</option>";
                                    }
                                    ?>
                                </select>
                                <div id="result">

                                </div>
                            </form>
                            <?php
                        }
                        else if($_GET['action'] === "delete"){
                            ?>
                                <h3>Kustuta kasutajaid</h3>
                            <?php
                        }
                        else if($_GET['action'] === "news"){
                            ?>
                                <h3>Lisa uudiseid</h3>
                            <?php
                        }
                        else if($_GET['action'] === "rights"){
                            $users = getUsers($mysqli);
                            ?>
                                <h3>Muuda õiguseid</h3>
                                <?php
                                if(isset($_POST['editrights'])){

                                ?>
                                <script>
                                    swal({
                                        title: 'Kas oled kindel?',
                                        text: 'Kas oled kindel, et soovid selle kasutaja õiguseid muuta?.',
                                        type: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Jah!',
                                        cancelButtonText: 'Katkesta!'
                                    }).then(function () {
                                        $.ajax({
                                            url: "ajaxrequests/scriptChangeRights.php",
                                            type: "POST",
                                            data: {
                                                id: <?php echo '\''.$_POST["changerights"].'\'' ?>,
                                                rights: <?php echo '\''.$_POST["rights"].'\'' ?>
                                            },
                                            dataType: "html",
                                            success: function () {
                                                swal("Valmis!", "Õiguseid edukalt muudetud!", "success");
                                            },
                                            error: function (xhr, ajaxOptions, thrownError) {
                                                swal("Viga!", "Proovi uuesti!", "error");
                                            }
                                        });
                                    });
                                </script>
                            <?php
                            }

                            ?>
                                <form method="POST" action="">
                                    <select name="changerights" id="usersRights" class='form-control'>
                                        <option>Vali kasutaja...</option>
                                        <?php

                                        while($row = $users->fetch_array(MYSQLI_ASSOC)){
                                            echo "<option value='$row[username]' >$row[username]</option>";
                                        }
                                        ?>
                                    </select>
                                    <div id="result">

                                    </div>
                                </form>
                            <?php
                        }else{
                            ?>
                                <h3>Sellist lehekülge ei eksisteeri!</h3>
                            <?php
                        }
                    }
                ?>
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
