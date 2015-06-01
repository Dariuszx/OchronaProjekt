<?php
    session_start();

    include_once("tools.php");

    $error='';

    if (isset($_POST['submit'])) {

        try {
            $userData = new UserData();
            $database = new Database();

            $nickname = $_POST['username'];
            $password = $_POST['password'];
            $userData->validateInputData($nickname);
            $userData->validateInputData($password);

            $userData->setNickname($nickname);
            $userData->setPassword($password);

            $userData->setSalt($database->getSalt($nickname));

            $userData->finalizeLoginData();
            $user_id = $database->userAuthentication($userData->getNickname(), $userData->getHash());

            $_SESSION['user_id'] = $user_id;
            $_SESSION['nickname'] = $userData->getNickname();
            header("location: profile.php");

        } catch( Exception $e ) {
            //$error = $e->getMessage();
            $error = $e;
        }
    }