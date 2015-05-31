<?php
    session_start();

    include("mysql_connect.php");
    $error='';

    if (isset($_POST['submit'])) {
        if (empty($_POST['username']) || empty($_POST['password'])) {
            $error = "Nieprawidłowe hasło lub nazwa użytkownika.";
        }
        elseif(strlen($_POST['username']) < 5 || strlen($_POST['password']) < 5 || strlen($_POST['username']) > 20 || strlen($_POST['username']) > 20) {
            $error = "Nieprawidłowe hasło lub nazwa użytkownika.";
        }
        else
        {
            $username=$_POST['username'];
            $password=$_POST['password'];

            $username = stripslashes($username);
            $password = stripslashes($password);
            $username = mysql_real_escape_string($username);
            $password = mysql_real_escape_string($password);


            $query = mysql_query("select salt from users where nickname='$username'", $connection);
            $row = mysql_fetch_array($query,MYSQL_ASSOC);

            $hash = hash('sha512', $password+""+$row['salt']);
            $query = mysql_query("select 1 from users where nickname='$username' AND password='$hash'", $connection);
            $nums = mysql_num_rows($query);

            if( $nums == 1) {
                mysql_close($connection);
                $_SESSION['login_user']=$username;
                header("location: profile.php");
            } else {
                $error = "Podana nazwa użytkownika lub hasło jest nieprawidłowe.";
            }

            mysql_close($connection); // Closing Connection
        }
    }