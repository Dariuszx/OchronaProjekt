<?php
    include('session.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Home Page</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="profile">
    <b id="welcome">Welcome : <i><?php echo $_SESSION['nickname']; ?></i></b>
    <b id="logout"><a href="logout.php">Log Out</a></b>
</div>
</body>
</html>