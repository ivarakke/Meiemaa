<?php
function redirectTo($url){
    header("Location: $url");
}

function register($user, $email, $pass, $repeatpass){
    $errors = "";
    global $mysqli;
    if(empty($user) || empty($email) || empty($pass) || empty($repeatpass)){
        $errors = ($errors."Täida kõik väljad! <br>");
    }
    if(strlen($user) > 50){
        $errors = ($errors."Kasutajanimi ei tohi olla pikem kui 50 tähemärki! <br>");
    }

    if(strlen($pass) < 6){
        $errors = ($errors."Parool peab olema pikem kui 6 tähemärki! <br>");
    }

    if(strlen($pass) > 50){
        $errors = ($errors."Parool ei tohi olla pikem kui 50 tähemärki! <br>");
    }

    if(strlen($email) > 100){
        $errors = ($errors."Email ei tohi olla pikem kui 100 tähemärki! <br>");
    }

    if(!($pass === $repeatpass)){
        $errors = ($errors."Paroolid ei klapi <br>");
    }

    $userExists = $mysqli->query("SELECT username FROM users WHERE username = '$user'");
    $rows = $userExists->num_rows;

    if(!($rows == 0)){
        $errors = ($errors." Kasutajanimi on juba kasutusel! <br>");
    }

    $emailExists = $mysqli->query("SELECT email FROM users WHERE email = '$email'");
    $rows = $emailExists->num_rows;

    if(!($rows == 0)){
        $errors = ($errors."<br> Email on juba kasutusel! <br>");
    }

    if(empty($errors)){
        $passSecure = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("INSERT INTO users (username, password, email, rights, ipaddress, added_at, edited_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("sssss", $user, $passSecure, $email, $rights, $ip);
        $ip = $_SERVER["REMOTE_ADDR"];
        $rights = DEFAULT_RIGHTS;
        if($stmt->execute()){
            $errors = "Kasutaja edukalt registreeritud!";
            $stmt->close();
            return "<div class='alert alert-success'>".$errors."</div>";
        }else{
            $stmt->close();
            $errors = "Kasutaja lisamine andmebaasi ebaõnnestus!";
            return "<div class='alert alert-danger'>".$errors."</div>";
        }

    }else{
        return "<div class='alert alert-danger'>".$errors."</div>";
    }
}

function checkbrute($user_id, $mysqli) {
    $now = time();
    $now = $now - (5 * 60);

    // Kõik logimiskatsed viimase 5 minuti jooksul.
    $valid_attempts = $now;

    if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE user_id = ? AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $user_id);

        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    }
}

function login($user, $pass, $mysqli){
    $errors = "";
    if($stmt = $mysqli->prepare("SELECT id, username, password FROM users WHERE username = ?")){
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->store_result();

        $stmt->bind_result($user_id, $username, $db_pass);
        $stmt->fetch();

        if($stmt->num_rows() == 1){
            if(checkbrute($user_id, $mysqli) == true){
                $errors = "Sinu kasutaja on lukus, kuna oled 5 minuti jooksul liiga palju kordi valed andmed sisestanud!";
            }else{
                if(password_verify($pass, $db_pass)){
                    $ban = getBan($user_id);
                    if($ban['ban'] != 0){
                        $errors = "Sinu kasutajal on mängukeeld!";
                        
                    }else {
                        $user_browser = $_SERVER['HTTP_USER_AGENT'];

                        $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                        $_SESSION['user_id'] = $user_id;
                        // XSS protection as we might print this value
                        $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
                        $_SESSION['username'] = $username;
                        redirectTo("home");
                        exit();
                    }
                }else{
                    $now = time();
                    $mysqli->query("INSERT INTO login_attempts(user_id, time) VALUES ('$user_id', '$now')");
                    $errors = "Sisestasid vale parooli!";
                }
            }
        }else{
            $errors = "Sellise nimega kasutajat ei eksisteeri!";
        }
    }
    return($errors);
}

function login_check($mysqli) {
    if (isset($_SESSION['user_id'],
        $_SESSION['username'])){

        $user_id = $_SESSION['user_id'];
        $username = $_SESSION['username'];

        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        if ($stmt = $mysqli->prepare("SELECT password FROM users WHERE id = ? LIMIT 1")) {

            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($password);
                $stmt->fetch();
                return true;

            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function esc_url($url) {

    if ('' == $url) {
        return $url;
    }

    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;

    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }

    $url = str_replace(';//', '://', $url);

    $url = htmlentities($url);

    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);

    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

function getRights($username, $mysqli){
    $stmt = $mysqli->prepare("SELECT rights FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows >= 1){
        $stmt->bind_result($rights);
        $stmt->fetch();
        return $rights;
    }
}

function getUsers($mysqli){
    $stmt = $mysqli->prepare("SELECT username, ID FROM users");
    $stmt->execute();
    $users = $stmt->get_result();
    return $users;
}

function getBan($id){
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT ban FROM users WHERE ID = '$id'");
    $stmt->execute();
    $banned = $stmt->get_result();
    $ban = $banned->fetch_array();

    return $ban;
}
?>