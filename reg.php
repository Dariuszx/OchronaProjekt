<?php
session_start();

include_once("tools.php");

if (isset($_POST['submit'])) {

    $error='';

    try {

        if( $_POST['password1'] != $_POST['password2'])
            throw new Exception("Passwords are not the same!");

        $database = new Database();
        $userData = new UserData();

        $userData->setNickname($_POST['username']);
        $userData->setPassword($_POST['password1']);
        $userData->setEmail($_POST['email']);

        $validatedData = $userData->getData();

        include_once 'securimage/securimage.php';
        $securimage = new Securimage();

        if ($securimage->check($_POST['captcha_code']) == false) {
            throw new Exception("Wrong captcha code!");
        }

        $user_id = $database->insertUser($validatedData);

        $_SESSION['nickname'] = $userData->getNickname();
        $_SESSION['user_id'] = $user_id;

        header("location: profile.php");

    } catch(Exception $e) {
        $error = $e->getMessage();
    }

}