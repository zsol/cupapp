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
 * The "user email confirmation" action
 *
 * @package    actions
 * @subpackage confirm
 * @author     Agoston Fung <agoston.fung@gmail.com>
 * @version    SVN: $Id$
 */
class confirmAction extends sfAction {
    public function execute( $request ){
        $i18n = sfContext::getInstance()->getI18N();
        
        if ($request->hasParameter('code') && $request->hasParameter('username')) {
            $username = $request->getParameter('username');
            $code = $request->getParameter('code');

            if ($user = sfGuardUserPeer::retrieveByUsername($username)) {
                if ($user->getIsActive()) {
		  $this->getUser()->setFlash('notice', $i18n->__('Your email is already confirmed.'));
		  $this->redirect('@homepage');
                }
                if ($user->getSalt() == $code) {
                    $user->setIsActive(true);
                    $user->save();

                    $this->getUser()->setFlash('success_message', $i18n->__('Email is confirmed. Now you can log in.') );
                    $this->redirect('@homepage');
                }
            }
            else {
                $this->forward404();
            }
        }
        else {
            $this->forward404();
        }
    }
}
