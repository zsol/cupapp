<?php

require_once dirname(__FILE__).'/../lib/replayadminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/replayadminGeneratorHelper.class.php';

/**
 * replayadmin actions.
 *
 * @package    Cupapp
 * @subpackage replayadmin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class replayadminActions extends autoReplayadminActions
{
  public function executeListReloadReplay(sfWebRequest $request)
  {
    $this->Replay = $this->getRoute()->getObject();
    if ($this->Replay->parseData()) {
      $this->Replay->save();
      $this->getUser()->setFlash('notice', "Replay data refreshed successfully");
    } else {
      $this->getUser()->setFlash('error', "Refresh failed!");
    }
    
    $this->redirect('@replay_replayadmin');
  }
}
