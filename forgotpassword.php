<?php

include('header.html');
include('fp.php');

?>

<div class="tablica">

    <h1 style="text-align: center;">Przypomnij hasło!</h1>
    <p style="color: red; font-size: 80%;text-align:center;"><?php echo $error; ?></p>
    <p style="color: greenyellow; font-size: 80%;text-align:center;"><?php echo $good; ?></p>
    <form style="width: 50%;margin-left:auto; margin-right:auto;" method="post" action="">
        <input autocomplete="off" type="text" name="email" placeholder="Wprowadź swój adres email, na który zarejestrowałeś konto" required="required" />
        <input style="width:50%;margin-left:auto; margin-right:auto;" type="submit" name="submit" class="btn btn-primary btn-block btn-large" value="Zaloguj się"  />
    </form>

</div