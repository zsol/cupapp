<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
require_once(dirname(__FILE__).'/../config/getEnvironment.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('backend', $env, $debug);
sfContext::createInstance($configuration)->dispatch();
