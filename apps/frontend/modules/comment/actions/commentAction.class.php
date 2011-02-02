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
 * @package    actions
 * @subpackage comment
 * @author     Agoston Fung <agoston.fung@gmail.com>
 * @version    SVN: $Id$
 */
class commentAction extends sfAction
{

    public function execute($request) {

        $form = new ReplayCommentForm();
        $user = $this->getUser();
        if (!$user->isAllowedToComment()) {
            $this->redirect('@homepage');
        }

        if ($request->isMethod('post')) {
            $form->bind($request->getParameter('replay_comment'));
            if ($form->isValid()) {
                $comment = $form->getObject();
                $comment->setUserId($user->getId());
                $comment->setCulture($user->getCulture());
                $comment->setComment($form->getValue('comment'));
                $comment->setReplayId($form->getValue('replay_id'));
                $comment->save();

                $userProfile = $user->getGuardUser()->getProfile();
                $userProfile->setLastCommented(time());
                $userProfile->save();

                $replay = $comment->getReplay();
                sfProjectConfiguration::getActive()->loadHelpers('Url');
                $this->redirect(url_for('@viewreplay?id='.$replay->getId().'&name='.$replay.'#comment_form'));
            }

        }
        $this->redirect('@homepage');
    }
}