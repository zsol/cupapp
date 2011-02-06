<?php

/**
 * Replay form.
 *
 * @package    Cupapp
 * @subpackage form
 * @author     Your name here
 */
class ReplayForm extends BaseReplayForm
{
  public function configure()
  {
    unset($this['game_info'], $this['avg_apm'], $this['players'], 
	  $this['map_name']);
  }
}
