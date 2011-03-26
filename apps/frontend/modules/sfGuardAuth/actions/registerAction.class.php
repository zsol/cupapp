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
 * The "register" action
 *
 * @package    actions
 * @subpackage register
 * @author     Agoston Fung <agoston.fung@gmail.com>
 * @version    SVN: $Id$
 */
class registerAction extends sfAction
{

    public function execute($request) {
        $this->form = new registerForm();
        $i18n = sfContext::getInstance()->getI18N();

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
            if ($this->form->isValid()) {
                $parameters = $request->getParameter($this->form->getName());

                //Create user object
                $user = new sfGuardUser();
                $user->setUsername($parameters['username']);
                $user->setIsActive(false);
                $user->setPassword($parameters['password1']);
                $user->save();

                //Create profile object
                $userProfile = new sfGuardUserProfile();
                $userProfile->setUserId($user->getId());
                $userProfile->setEmail($parameters['email']);

                //Create avatar
                $file = $this->form->getValue('avatar');
                if ($file) {
                    $filePath = $file->getTempName();
                    try {
                        $userProfile->createAndSaveAvatar($filePath);
                    } catch (Exception $e) {
		      $this->getUser()->setFlash('error_message', $e->getMessage());
		      $this->redirect('@homepage');
                    }
                }

                $userProfile->setModifiedAt(time());
                $userProfile->save();

                $params = array();
                $params['username'] = $user->getUsername();
                $params['salt'] = $user->getSalt();

                $html = $this->getPartial('email/confirmEmailHtml', $params);
                $text = $this->getPartial('email/confirmEmailTxt', $params);

                if (sfConfig::get('app_send_confirmation_email')) {
                    $user->sendValidationEmail($html, $text);
                    $this->getUser()->setFlash('success_message', $i18n->__('Registration complete! You will get an email with the confirmation link.'));
                } else {
                    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
                    $this->getUser()->setFlash('success_message', $i18n->__('Registration complete! You will get an email with the confirmation link.') . '<a href="' . url_for('@confirmemail?username=' . $user->getUsername() . '&code=' . $user->getSalt()) . '">link</a>');
                }

                $this->redirect('@homepage');
            } else {
                $this->getUser()->setFlash('error_message', $i18n->__('There were errors in the submission. See the form for details.'));
            }
        }
    }

}