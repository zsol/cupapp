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
 * The "upload replay" action
 *
 * @package    actions
 * @subpackage replay
 * @author     Agoston Fung <agoston.fung@gmail.com>
 * @version    SVN: $Id$
 */
class uploadAction extends sfAction
{

    public function execute($request) {

        $i18n = sfContext::getInstance()->getI18N();
        $sfUser = $this->getUser();
        $form = new ReplayUploadForm();
        $this->form = $form;

        if (sfConfig::get('app_replay_disable_upload')) {
            return;
        }

        if ($request->isMethod('post')) {
            $form->bind($request->getParameter('replay'), $request->getFiles('replay'));
            if ($form->isValid()) {

                /*
                 * Prepare data
                 */
                $newReplay = $form->getObject();
                $newReplay->setUserId($sfUser->getId());
                $newReplay->setCreatedAt(time());
                $newReplay->setDescription($form->getValue('description'));
                $newReplay->setCategoryId($form->getValue('category_id'));

                /*
                 * Save file
                 */
                $file = $form->getValue('replay_file');
                $path = pathinfo($file->getOriginalName());
                $extension = $path['extension'];
                if (strtolower($extension) != 'sc2replay') {
                    $this->getUser()->setFlash('error_message', $i18n->__('Wrong extension!'));
                    return sfView::SUCCESS;
                }
                try {
                    $filename = $newReplay->generatePreparedFileName();
                } catch (Exception $e) {
                    $this->getUser()->setFlash('error_message', $e->getMessage());
                    return sfView::SUCCESS;
                }

                $newReplay->setFileName($filename);
                $file->save($newReplay->getFilePath());

                $result = $newReplay->parseData();

                if (!$result) {
                    unlink($fileSavePath);
                    $this->getUser()->setFlash('error_message', $i18n->__('File cannot be parsed!'));
                    return sfView::SUCCESS;
                }

                $newReplay->save();

                //Upload guard user that has just uploaded
                $guardUserProfile = $sfUser->getGuardUser()->getProfile();
                $guardUserProfile->setLastUploaded(time());
                $guardUserProfile->save();

                $this->getUser()->setFlash('success_message', $i18n->__('Upload successfull!'));
                $this->redirect('replay/browse');
            }
        }
    }

}