<?php

    function test(&$zmienna) {
        $zmienna++;
    }

    $zmienna = 10;
    test($zmienna);
    print $zmienna;