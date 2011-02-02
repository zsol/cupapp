<?php

if ($_SERVER['SERVER_NAME'] == 'test.cupapp.com') {
    $env = 'dev';
    $debug = true;
}
else if ($_SERVER['SERVER_NAME'] == 'sc2.blizzfanatic.com') {
    $env = 'prod';
    $debug = false;
}

?>