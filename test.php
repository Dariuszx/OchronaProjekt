<?php

$to = "marazmad.01@gmail.com";
$subject = "Test mail";
$message = "My message";
$from = "webp@gmail.com";
$headers = "From:" . $from;
mail($to,$subject,$message,$headers);