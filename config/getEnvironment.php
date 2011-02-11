<?php

if ($_SERVER['SERVER_NAME'] == 'test.cupapp.com') {
    $env = 'dev';
    $debug = true;
}
else if ($_SERVER['SERVER_NAME'] == 'www.cupapp.com' || $_SERVER['SERVER_NAME'] == 'cupapp.com') {
    $env = 'prod';
    $debug = false;
}

?>
