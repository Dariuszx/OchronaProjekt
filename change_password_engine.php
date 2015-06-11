<?php

    include_once('tools.php');

    $error = '';

    if(isset($_POST['submit'])) {

        try {
            $current_password = $_POST['current_password'];
            $password1 = $_POST['password1'];
            $password2 = $_POST['password2'];

            $data = new DataPreferences();
            $user = new UserData();
            $database = new Database();

            $data->validateInputData($current_password);
            $data->validateInputData($password1);
            $data->validateInputData($password2);

            if( $password1 != $password2 ) throw new Exception("Given passwords are not the same!");


            $user->setNickname($_SESSION['nickname']);
            $user->setPassword($current_password);

            $user->setSalt($database->getSalt($_SESSION['nickname']));

            $user->finalizeLoginData();

            (new UserAccount())->logIn($_SESSION['nickname'],$user->getHash());

            $user->changePassword($password1);

            $database->updatePassword($_SESSION['user_id'], $user->getHash(), $user->getSalt());

            header("location: profile.php");

        } catch(Exception $e) {
            $error = $e->getMessage();
        }

    }