<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->
  get('/en')->

  with('request')->begin()->
    isParameter('module', 'cms')->
    isParameter('action', 'home')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '/You are not logged in/')->
    checkElement('body', '/Home/')->
  end()
;

$browser->
  get('/en/rules')->

  with('request')->begin()->
    isParameter('module', 'cms')->
    isParameter('action', 'rules')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '/Terms and Conditions/')->
  end()
  ;

$browser->
  get('/en/contact')->
  
  with('request')->begin()->
    isParameter('module', 'cms')->
    isParameter('action', 'contact')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '/Contact/')->
  end()
  ;