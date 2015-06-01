<?php
include('reg.php');

if(isset($_SESSION['login_user']))
{
    header("location: profile.php");
}

include('header.html');
?>

<div class="login">
    <h1>Rejestracja</h1>
    <p style="color: red; font-size: 80%;text-align:center;"><?php if(isset($error)) echo $error; ?></p>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Login" required="required" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>" />
        <input type="email" name="email" placeholder="your@email" required="required" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"/>
        <input type="password" name="password1" placeholder="Password" required="required" />
        <input type="password" name="password2" placeholder="Retype password" required="required" />
        <input type="submit" name="submit" class="btn btn-primary btn-block btn-large" value="Zarejestruj"  />
    </form>
</div>

<?php
include('bottom.html');