<?php

/**
 * feed actions.
 *
 * @package    Cupapp
 * @subpackage feed
 * @author     Your name here
 * @version    SVN: $Id$
 */
class feedActions extends sfActions
{
  public function executeLatestReplays(sfWebRequest $request)
  {
    $i18n = sfContext::getInstance()->getI18N();
    $feed = new sfAtom1Feed();
    $feed->initialize(array('title' => 'CupApp - ' . $i18n->__('Last uploaded replays'),
                            'link' => 'http://www.cupapp.com/'));

    $num = sfConfig::get('app_feed_latest_replays_num', 5);

    if ($request->getParameter('num'))
      $num = $request->getParameter('num');

    $replays = ReplayPeer::getLastReplays($num);
    
    foreach ($replays as $replay)
    {
      $item = new sfFeedItem();
      $item->initialize(array('title' => $replay->getReplayGameType()->getName() .' - '. $replay->getMapName().' - '. $replay->getPlayersName(', '),
                              'link' => '@viewreplay?id='.$replay->getId().'&name='.$replay,
                              'authorName' => $replay->getsfGuardUser()->getUserName(),
                              'pubDate' => strtotime($replay->getCreatedAt('Y-m-d H:i:s')),
                              'uniqueId' => $replay->getId(),
                              'description' => $replay->getDescription()
                              ));
      $feed->addItem($item);
    }
    
    $this->feed = $feed;                        
  }
}
