<?php
include('reg.php');

if(isset($_SESSION['login_user']))
{
    header("location: profile.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Zarejestruj się</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="main">
    <div id="login">
        <h2>Registration form</h2>
        <form action="" method="post">
            <label>Nazwa użytkownika :</label>
            <input maxlength="20" id="name" name="username" placeholder=" login" type="text">
            <label>Hasło :</label>
            <input maxlength="20" id="password1" name="password1" placeholder=" **********" type="password">
            <label>Powtórz hasło:</label>
            <input maxlength="20"id="password2" name="password2" placeholder=" **********" type="password">
            <input name="submit" type="submit" value=" Zarejestruj ">
            <span><?php echo $error; ?></span>
        </form>
    </div>
</div>
</body>
</html>