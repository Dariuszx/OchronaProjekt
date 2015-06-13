<?php
include('session.php');

include('tools.php');

include('header.html');

include('menu.php');

$devices = (new Database())->getDevice($_SESSION['user_id']);

?>

<div class="tablica">

    <h1 style="text-align: center;">Podłączone urządzenia</h1>

    <?php

    echo "<div class='note'>";
        for($i=0; $i < sizeof($devices); $i++) {
            echo "<table style=\"width:100%;\"><tr style=\"color:darkgray;\">";
            echo "<td>#".$devices[$i]['device_id']."</td>";
            echo "<td style=\"text-align: right;\">";
            echo $devices[$i]['login_date']."</td></tr>";
            echo "<tr><td colspan='3'>".$devices[$i]['device_name']."</td></tr>";
            echo "</table>";
        }
    echo "</div>";

    ?>


</div
