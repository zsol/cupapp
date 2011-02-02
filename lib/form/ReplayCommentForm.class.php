<?php

/**
 * ReplayComment form.
 *
 * @package    Cupapp
 * @subpackage form
 * @author     Your name here
 */
class ReplayCommentForm extends BaseReplayCommentForm
{
  public function configure()
  {
      $this->setWidget('replay_id', new sfWidgetFormInputHidden());
      $this->useFields(array('comment', 'replay_id'));
  }
}
