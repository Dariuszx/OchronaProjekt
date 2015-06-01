<?php
    session_start();
//    include("mysql_connect.php");
//
//    $user_check = $_SESSION['login_user'];
//
//    $ses_sql = mysql_query("select nickname from users where nickname='$user_check'", $connection);
//    $row = mysql_fetch_assoc($ses_sql);
//    $login_session =$row['nickname'];

    if(!isset($_SESSION['user_id'])){
        mysql_close($connection); // Closing Connection
        header('Location: index.php'); // Redirecting To Home Page
    }