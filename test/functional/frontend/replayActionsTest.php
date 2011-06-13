<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->
  get('/')->
  with('request')->begin()->info('Default module/action is cms/home')->
    isParameter('module', 'cms')->
    isParameter('action', 'home')->
  end()->
  with('response')->begin()->
    info('Home page appears')->isStatusCode(200)->checkElement('body', '/Home/')->
    info('Profile box appears')->checkElement('body', '/Profile/')->
    info('Last uploaded replays appear')->checkElement('body', '/Last uploaded replays/')->
    info('Latest comments appear')->checkElement('body', '/Latest comments/')->
    info('My replays box does not appear')->checkElement('body', '!/My replays/')->
  end()
  ;

/*
 * Login page appears
 */
$browser->info('Login page')->
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
$browser->info('Register page')->
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
$browser->info('Upload only when logged in')->
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
$browser->info('Browse page')->
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
$browser->info('Login CSRF')->
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
$browser->info('Login as superuser')->
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

  with('response')->begin()->info('Profile status as logged in')->
    isStatusCode(200)->
    checkElement('body', '/Logged in as superadmin/')->
    info('My replays appear after logging in')->checkElement('body', '/My replays/')->
  end()
;

/*
 * After login you can reach the upload page
 */
$browser->info('Upload enabled after logging in')->
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
 * View replay from the browse page
 */
$browser->info('View replay from browse page')->
  get('/replay/browse')->
  click('Details')->
  with('request')->begin()->
    isParameter('module', 'replay')->
    isParameter('action', 'view')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '/Replay details/')->
    checkElement('body', '/Message log/')->
    checkElement('body', '/How to share this replay/')->
    checkElement('body', '/Replay comments/')->
  end()
  ;

/*
 * Commenting works
 */
$browser->info('Commenting')->
  click('Send comment', array('replay_comment' => array('comment' => 'Test comment sallalala')))->
  with('response')->isRedirected()->
  followRedirect()->info('Comment appears')->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '/Test comment sallalala/')->
    info('Comment flood defence')->
    checkElement('body', '!/Send comment/')->
    checkElement('body', '/You can only comment/')->
  end()
  ;


/*
 * Log out works
 */
$browser->info('Log out')->
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