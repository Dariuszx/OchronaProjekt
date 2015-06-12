<?php

    include_once('tools.php');

    $error = '';
    $good = '';

    if(isset($_POST['submit'])) {

        try {
            $user = new UserData();
            $database = new Database();
            $userAccount = new UserAccount();

            $email = $_POST['email'];
            $user->validateInputData($email);
            $user->checkEmail($email);

            $user_id = $database->checkEmail($email);

            if(!$userAccount->canLogIn()) {
                throw new Exception("Your IP address has been blocked!");
            }

            if($user_id == -1) {
                $userAccount->addLoginAttempt();
                throw new Exception("Wrong email address!");
            }

            $password = $user->randomPassword();

            $user->changePassword($password);
            $database->updatePassword($user_id, $user->getHash(), $user->getSalt());

            $database->sendPassword($email,$password);
            $good = 'Your password has been sent to your email address.';

        } catch(Exception $e) {
            $error = $e->getMessage();
        }

    }