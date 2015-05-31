<?php
    include('login.php');

    if(isset($_SESSION['login_user']))
    {
        header("location: profile.php");
    }
    include('header.html');
?>

    <div class="login">
        <h1>Login</h1>
        <p style="color: red; font-size: 80%;text-align:center;"><?php echo $error; ?></p>
        <form method="post" action="">
            <input type="text" name="username" placeholder="Username" required="required" />
            <input type="password" name="password" placeholder="Password" required="required" />
            <input type="submit" name="submit" class="btn btn-primary btn-block btn-large" value="Zaloguj się"  />
        </form>
        <p class="forgot-password"><a href="register.php">Zarejestruj się!</a>
        </p>
    </div>


<?php
    include('bottom.html');