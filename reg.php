<?php
session_start();

include("mysql_connect.php");
$error='';

if (isset($_POST['submit'])) {
    if (empty($_POST['username']) || empty($_POST['password1']) || empty($_POST['password1'])) {
        $error = "Niepoprawnie wypełniono formularz rejestracyjny!";
    }
    elseif( strlen($_POST['username']) < 5 ||
            strlen($_POST['password1']) < 5 ||
            strlen($_POST['username']) > 20 ||
            strlen($_POST['password1']) > 20) {

            $error = "Niepoprawnie wypełniono formularz rejestracyjny!";
    }
    else
    {
        $username=$_POST['username'];
        $password1=$_POST['password1'];
        $password2=$_POST['password2'];

        $username = stripslashes($username);
        $password1 = stripslashes($password1);
        $password2 = stripslashes($password2);

        $username = mysql_real_escape_string($username);
        $password1 = mysql_real_escape_string($password1);
        $password2 = mysql_real_escape_string($password2);


        $query = mysql_query("select 1 from users where nickname='$username'", $connection);
        $rows = mysql_num_rows($query);

        if ($rows == 1) {
            $error = "Użytkownik o podanej nazwie już istnieje w bazie!";
        } elseif ($password1 != $password2) {
            $error = "Podane hasła są różne!";
        } else {
            $salt = openssl_random_pseudo_bytes(128);
            $hash = hash('sha512', $password1 + "" + $salt);

            $result = mysql_query("INSERT INTO users (nickname, salt, password) VALUES ('$username', '$salt', '$hash')", $connection);

            $_SESSION['login_user']=$username;
            header("location: profile.php");
        }
        mysql_close($connection);
    }
}