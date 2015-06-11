<?php
    include('session.php');

    include("addStatus.php");

    include('header.html');

    include('menu.php');
?>



<div class="tablica">

    <form method="post" action="">
        <input type="text" name="note" placeholder="O czym teraz myślisz..." required="required" />
        <p style="width: 30%;margin-right:auto; margin-left: auto;"><input type="submit" name="submit" class="btn btn-primary btn-block btn-large" value="Napisz na tablicy"  /></p>
    </form>

    <?php

        for( $i=0; $i < count($notes); $i++) {
            echo "<div class='note'>";
                echo "<table style=\"width:100%;\"><tr style=\"color:darkgray;\">";
                    echo "<td>#".$notes[$i]['note_id']."</td>";
                echo "<td style=\"text-align: right;\">";
                    echo "<span style=\"margin:0px;padding-right: 10px;\" class=\"forgot-password\">";
                        echo "<a href=\"?delete=".$notes[$i]['note_id']."\">Usuń</a>";
                    echo "</span>";
                    echo $notes[$i]['date_added']."</td></tr>";
                echo "<tr><td colspan='3'>".$notes[$i]['note']."</td></tr>";
                echo "</table>";
            echo "</div>";
        }
    ?>

</div>

<?php
    include('bottom.html');

