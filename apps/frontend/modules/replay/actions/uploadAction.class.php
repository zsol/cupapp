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

  private static $i18n;

    public function execute($request) {
        self::$i18n = sfContext::getInstance()->getI18N();
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
                if (strtolower($extension) == 'zip') {
                  if (!$this->unpackZip($file, $form->getValue('new_category_name')))
                    return sfView::SUCCESS;
                }
                else if (strtolower($extension) == 'sc2replay') {
                  $newReplay = $this->processReplay($newReplay, $file);
                  if (!$newReplay)
                    return sfView::SUCCESS;
                  
                  $newReplay->save();
                }
                else {
                  $this->getUser()->setFlash('error_message', self::$i18n->__('Wrong extension!'));
                  return sfView::SUCCESS;
                }

                //Upload guard user that has just uploaded
                $guardUserProfile = $sfUser->getGuardUser()->getProfile();
                $guardUserProfile->setLastUploaded(time());
                $guardUserProfile->save();

                $this->getUser()->setFlash('success_message', self::$i18n->__('Upload successfull!'));
                $this->redirect('replay/browse');
            }
        }
    }

    private function unpackZip($file, $category) {
      $zip = new ZipArchive;
      if ($zip->open($file->getTempName()) === TRUE) {
        
        $newCategory = new ReplayCategory;
        $newCategory->setName($category);
        $reps = array();
        for ($i = 0; $i < $zip->numFiles; ++$i) {
          $newReplay = new Replay;
          $newReplay->setUserId($this->getUser()->getId());
          $rep = $this->processReplay($newReplay, $zip->getStream($zip->getNameIndex($i)));
          if ($rep)
            $reps[] = $rep;
        }
        $zip->close();
        
        $newCategory->save();
        foreach ($reps as $rep) {
          $rep->setCategoryId($newCategory->getId());
          $rep->save();
        }
        return true;
      }
      $this->getUser()->setFlash('error_message', self::$i18n->__('Bad zip archive!'));
      return false;
    }

    private function processReplay($newReplay, $file) {
      try {
        $filename = $newReplay->generatePreparedFileName();
      } catch (Exception $e) {
        $this->getUser()->setFlash('error_message', basename($file) . ": " . $e->getMessage());
        return false;
      }
      
      if ($file instanceof sfValidatedFile) {
        $file->save($newReplay->getFilePath());
      } else {
        $dest = fopen($newReplay->getFilePath(), 'wb');
        stream_copy_to_stream($file, $dest);
        fclose($dest);
      }
      
      $result = $newReplay->parseData();
      
      if (!$result) {
        if ($file instanceof sfValidatedFile) {
          $name = $file->getOriginalName();
        } else {
          $name = stream_get_meta_data($file);
          $name = $name['uri'];
        }
        $this->getUser()->setFlash('error_message', self::$i18n->__('%%FNAME%%: File cannot be parsed!', array('%%FNAME%%' => $name)));
        return false;
      }
      return $newReplay;
    }

}