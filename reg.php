<?php
session_start();

include_once("tools.php");

if (isset($_POST['submit'])) {

    $error='';

    try {

        if( $_POST['password1'] != $_POST['password2'])
            throw new Exception("Password are not the same!");

        $database = new Database();
        $userData = new UserData();

        $userData->setNickname($_POST['username']);
        $userData->setPassword($_POST['password1']);
        $userData->setEmail($_POST['email']);

        $validatedData = $userData->getData();

        $user_id = $database->insertUser($validatedData);


        $_SESSION['nickname'] = $username;
        $_SESSION['user_id'] = $user_id;
        header("location: profile.php");

    } catch(Exception $e) {
        $error = $e;
    }

}