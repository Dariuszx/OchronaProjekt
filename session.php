<?php
    session_start();


    if(!isset($_SESSION['user_id'])){
        mysql_close($connection);
        header('Location: index.php');
    }

    if (!isset($_SESSION['inicjuj']))
    {
        session_regenerate_id();
        $_SESSION['inicjuj'] = true;
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
    }


    if($_SESSION['ip'] != $_SERVER['REMOTE_ADDR'])
    {
        session_regenerate_id();
        $_SESSION['inicjuj'] = true;
    }