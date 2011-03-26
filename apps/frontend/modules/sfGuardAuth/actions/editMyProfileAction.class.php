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
 * The "edit my profile" action
 *
 * @package    actions
 * @subpackage profile
 * @author     Agoston Fung <agoston.fung@gmail.com>
 * @version    SVN: $Id$
 */
class editMyProfileAction extends sfAction
{

    public function execute($request) {
        $this->form = new editMyProfileForm();
        $i18n = sfContext::getInstance()->getI18N();

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
            if ($this->form->isValid()) {
                $parameters = $request->getParameter($this->form->getName());

                //Retrieve user object
                $user = $this->getUser()->getGuardUser();
                if ($parameters['password1']) {
                    $user->setPassword($parameters['password1']);
                }
                $user->save();

                //Retrieve profile object
                $userProfile = $user->getProfile();

                //Create avatar
                $file = $this->form->getValue('avatar');
                if ($file) {
                    $filePath = $file->getTempName();
                    if (!$userProfile->createAndSaveAvatar($filePath)) {
                        $this->getUser()->setFlash('error_message', $i18n->__('Image cannot be created.'));
                        $this->redirect('@homepage');
                    }
                }

                $userProfile->setModifiedAt(time());
                $userProfile->save();

                $this->getUser()->setFlash('success_message', $i18n->__('Profile updated successfully.'));

                //$this->redirect('@homepage');
            } else {
                $this->getUser()->setFlash('error_message', $i18n->__('There were errors in the submission. See the form for details.'));
            }
        }
    }

}