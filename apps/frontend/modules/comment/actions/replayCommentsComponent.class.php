<?php

/*
 * This file is part of the Starcraft2 Cup Application project
 * http://code.google.com/p/sc2-cup-app/
 *
 * (c) 2010 Agoston Fung <agoston.fung@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was ditributed with this source code.
 *
 */

/**
 * The "comment" action
 *
 * @package    components
 * @subpackage comment
 * @author     Agoston Fung <agoston.fung@gmail.com>
 * @version    SVN: $Id$
 */
class replayCommentsComponent extends sfComponent
{

    public function execute($request) {
        $c = new Criteria();
        $c->add(ReplayCommentPeer::CULTURE, $this->getUser()->getCulture());
        $c->addJoin(ReplayCommentPeer::USER_ID, sfGuardUserPeer::ID);
        $c->addJoin(sfGuardUserPeer::ID, sfGuardUserProfilePeer::USER_ID);
        $this->comments = $this->replay->getReplayComments($c);
    }

}