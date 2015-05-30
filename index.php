<?php
    include('login.php');

    if(isset($_SESSION['login_user']))
    {
        header("location: profile.php");
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Zaloguj się</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="main">
    <div id="login">
        <h2>Login Form</h2>
        <form action="" method="post">
            <label>UserName :</label>
            <input maxlength="20" id="name" name="username" placeholder=" login" type="text">
            <label>Password :</label>
            <input maxlength="20" id="password" name="password" placeholder=" **********" type="password">
            <input name="submit" type="submit" value=" Login ">
            <span><?php echo $error; ?></span>
        </form>
    </div>
    <p><a href="register.php">Zarejestruj się</a></p>
</div>
</body>
</html>