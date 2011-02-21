<?php

/**
 * language actions.
 *
 * @package    Cupapp
 * @subpackage language
 * @author     Your name here
 * @version    SVN: $Id$
 */
class languageActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  }

  public function executeChangeLanguage(sfWebRequest $request)
  {
    $form = new sfFormLanguage($this->getUser(),
			       array('languages' => array('en', 'hu')));

    $form->process($request);
    
    return $this->redirect('replayhome');
  }
}
