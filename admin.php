
<?php

include("layouts/header.php");
include("include/functions.php");
session_start();
include("include/connectMysql.php");
if(!login_check($mysqli)){
    redirectTo("login");
}else {
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

                                if(isset($_POST['delete'])){
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

                            ?>
                            <form method="POST" action="">
                                <select name="users" class='form-control'>
                                    <?php
                                    while($row = $users->fetch_array(MYSQLI_ASSOC)){
                                        echo "<option value='$row[ID]' >$row[username]</option>";
                                    }
                                    ?>
                                </select>
                                <button class="btn btn-danger" id="delete" name="delete" style="margin-top:5px;">Pane kasutajale mängukeeld!</button>
                            </form>
                            <?php
                        }else if($_GET['action'] === "delete"){
                            ?>
                                <h3>Kustuta kasutajaid</h3>
                            <?php
                        }else{
                            ?>
                                <h3>Lisa uudiseid</h3>
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
