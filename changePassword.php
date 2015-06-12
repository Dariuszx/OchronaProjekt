<?php
include('session.php');

include('change_password_engine.php');

include('header.html');

include('menu.php');

?>

<div class="tablica">

    <h1 style="text-align: center;">Zmień hasło</h1>
    <p style="color: red; font-size: 80%;text-align:center;"><?php echo $error; ?></p>
    <form style="width: 50%;margin-left:auto; margin-right:auto;" method="post" action="">
        <input type="password" name="current_password" placeholder="Aktualne hasło" required="required" />
        <input type="password" name="password1" placeholder="Nowe hasło" required="required" />
        <input type="password" name="password2" placeholder="Nowe hasło jeszcze raz" required="required" />
        <input style="width:50%;margin-left:auto; margin-right:auto;" type="submit" name="submit" class="btn btn-primary btn-block btn-large" value="Zaloguj się"  />
    </form>

</div
