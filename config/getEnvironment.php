<?php

if ($_SERVER['SERVER_NAME'] == 'cupapp.dyndns.info') {
    $env = 'dev';
    $debug = true;
}
else if ($_SERVER['SERVER_NAME'] == 'my.wild.dre.am') {
    $env = 'prod';
    $debug = false;
}

?>