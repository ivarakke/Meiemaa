
<?php
include("include/functions.php");
include("layouts/header.php");
include("include/connectMysql.php");
?>

<div class="row">
    <?php
    require('layouts/nav.php');
    ?>

    <div class="col-6">
        <div class="content">
            <?php
            if(isset($_POST['submit'])){
                $user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
                $repeatpass = filter_input(INPUT_POST, 'repeatpass', FILTER_SANITIZE_STRING);
                $errors = register($user, $email, $pass, $repeatpass);
                echo $errors;
            }
            ?>
            <div class="text-center" style="width:100%"><h3>Registreeru</h3></div>
            <hr>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="exampleInputUsername">Kasutajanimi</label>
                    <input type="text" name="user" class="form-control" id="exampleInputUsername" placeholder="Kasutajanimi" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email" required>
                    <small id="emailHelp" class="form-text text-muted">Me ei jaga sinu emaili kellegi teisega!</small>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Parool</label>
                    <input type="password" name="pass" class="form-control" id="exampleInputPassword1" placeholder="Parool" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword2">Korda parooli</label>
                    <input type="password" name="repeatpass" class="form-control" id="exampleInputPassword2" placeholder="Korda parooli" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <?php
    require('layouts/news.php');
    ?>
</div>



<?php
include("layouts/footer.php");
?>
