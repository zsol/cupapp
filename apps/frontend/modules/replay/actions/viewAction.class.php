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
 * The "view replay" action
 *
 * @package    actions
 * @subpackage replay
 * @author     Agoston Fung <agoston.fung@gmail.com>
 * @version    SVN: $Id$
 */
class viewAction extends sfAction
{

    public function execute($request) {
        if ($request->hasParameter('id')) {
            $id = $request->getParameter('id');
            $replay = ReplayPeer::retrieveByPK($id);

            if (!$replay) {
                $this->redirect('replay/browse');
            }

            $this->replay = $replay;
        } else {
            $this->redirect('replay/browse');
        }
    }

}