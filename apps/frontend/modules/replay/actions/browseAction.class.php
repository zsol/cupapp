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
 * The "browse replays" action
 *
 * @package    actions
 * @subpackage replay
 * @author     Agoston Fung <agoston.fung@gmail.com>
 * @version    SVN: $Id$
 */
class browseAction extends sfAction
{

    public function execute($request) {

        //Get old parameters from session
        $sfUser = $this->getUser();
        $oldParameters = array();
        if ($sfUser->hasAttribute('browseFilter')) {
            $oldParameters = $sfUser->getAttribute('browseFilter');
        } 

        //Update old parameters with new parameters
        $newParameters = $request->getParameter('filter', array());
        foreach($newParameters as $key => $value) {
            $oldParameters[$key] = $value;
        }
        $parameters = $oldParameters;

        //Store new parameters
        $sfUser->setAttribute('browseFilter', $parameters);

        $form = new ReplayFilterForm();
        $form->bind($parameters);
        $this->pager = ReplayPeer::getPagerByParameters($parameters,sfConfig::get('app_replay_pager_max_perpage', 10),$request->getParameter('page',1));
        $this->form = $form;

    }

}