
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("layouts/header.php");
include("include/functions.php");
include("include/connectMysql.php");
session_start();
?>

<div class="row">
    <?php
    require('layouts/nav.php');
    ?>
    <div class="col-6">
        <div class="content">
            <?php
            if (isset($_POST['user'], $_POST['pass'])) {
                $email = $_POST['user'];
                $password = $_POST['pass']; // The hashed password.
                $errors = login($email, $password, $mysqli);
                if (!$errors == "") {
                    echo "<div class='alert alert-danger'>$errors</div>";

                }
            }
            ?>
            <div class="text-center" style="width:100%"><h3>Logi sisse</h3></div>
            <hr>
            <form method="post" action="">
                <div class="form-group">
                    <label for="exampleInputUsername">Kasutajanimi</label>
                    <input type="text" name="user" class="form-control" id="exampleInputUsername"
                           placeholder="Kasutajanimi" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Parool</label>
                    <input type="password" name="pass" class="form-control" id="exampleInputPassword1"
                           placeholder="Parool" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
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
