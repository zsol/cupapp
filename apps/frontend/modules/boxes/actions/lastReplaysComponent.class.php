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
 * The "last uploaded replays" component
 *
 * @package    components
 * @subpackage replay
 * @author     Agoston Fung <agoston.fung@gmail.com>
 * @version    SVN: $Id$
 */
class lastReplaysComponent extends sfComponent {
    public function execute ($request) {
        $limit = sfConfig::get('app_boxes_last_uploaded_replays_num');
        $this->replays = ReplayPeer::getLastReplays($limit);
    }
}
