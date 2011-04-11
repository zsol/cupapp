<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

/*
 * Login page appears
 */
$browser->
  get('/en/login')->

  with('request')->begin()->
    isParameter('module', 'sfGuardAuth')->
    isParameter('action', 'signin')->
  end()->

  with('response')->begin()->
    isStatusCode(401)->
    checkElement('body', '/Login form/')->
  end()
;

/*
 * Register page displays
 */
$browser->
  get('/en/register')->

  with('request')->begin()->
    isParameter('module', 'sfGuardAuth')->
    isParameter('action', 'register')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '/Register/')->
  end()
;

/*
 * Upload page will be a 401 as we are not logged in and Login form displays
 */
$browser->
  get('/replay/upload')->

  with('request')->begin()->
    isParameter('module', 'replay')->
    isParameter('action', 'upload')->
  end()->

  with('response')->begin()->
    isStatusCode(401)->
    checkElement('body', '/Login form/')->
  end()
;

/*
 * Browse page displays
 */
$browser->
  get('/replay/browse')->

  with('request')->begin()->
    isParameter('module', 'replay')->
    isParameter('action', 'browse')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '/Browse replays/')->
  end()
;

/*
 * Normal login will create a CSRF problem
 */
$browser->
  post('/en/login',array('signin[username]' => 'loller', 'signin[password]' => '123'))->

  with('request')->begin()->
    isParameter('module', 'sfGuardAuth')->
    isParameter('action', 'signin')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '/You are not logged in/')->
  end()
;

$user = sfGuardUserPeer::retrieveByUsername('superadmin');
$user->setPassword('dummypassword');
$user->save();

/*
 * We can login as superuser
 */
$browser->
  get('/en/login')->

  click('Sign in', array( 'signin' => array(
      'username' => 'superadmin',
      'password' => 'dummypassword'
  )))->

with('form')->begin()->
        hasErrors(false)->
  end()->

  with('response')->isRedirected()
;

/*
 * After login the the profile box says your are logged in
 */
$browser->
  get('/')->
  with('request')->begin()->
    isParameter('module', 'cms')->
    isParameter('action', 'home')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '/Logged in as superadmin/')->
  end()
;

/*
 * After login you can reach the upload page
 */
$browser->
  get('/replay/upload')->
  with('request')->begin()->
    isParameter('module', 'replay')->
    isParameter('action', 'upload')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '/Logged in as superadmin/')->
    checkElement('body', '/Upload replay/')->
  end()
;

/*
 * Log out works
 */
$browser->
  get('/')->
  click('Log out')->
  with('response')->isRedirected()
  ;

$browser->
  get('/')->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '/You are not logged in\./')->
  end()
  ;