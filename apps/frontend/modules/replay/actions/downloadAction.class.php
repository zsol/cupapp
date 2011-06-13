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
 * The "download replay" action
 *
 * @package    components
 * @subpackage replay
 * @author     Agoston Fung <agoston.fung@gmail.com>
 * @version    SVN: $Id$
 */
class downloadAction extends sfAction
{

    public function execute($request) {
        $i18n = sfContext::getInstance()->getI18N();
        if ($request->hasParameter('id')) {
            $id = $request->getParameter('id');
            $replay = ReplayPeer::retrieveByPK($id);
            if (!$replay) {
                $this->getUser()->setFlash('error_message', $i18n->__('This replay doesn\'t exist in our database anymore.'));
                $this->redirect('@homepage');
            }

            $file = $replay->getFilePath();
            if (file_exists($file)) {

                $downLoadCount = $replay->getDownloadCount();
                $downLoadCount++;
                $replay->setDownloadCount($downLoadCount);
                $replay->save();

                $fileName = $replay . '.sc2replay';
                $response = $this->getResponse();
                $response->setContentType('application/binary');
                $response->setHttpHeader('Content-Disposition', 'attachment; filename=' . $fileName);
                $response->setHttpHeader('Content-Length', filesize($file));
                $response->setContent(file_get_contents($file));
                return sfView::NONE;
            } else {
                $this->getUser()->setFlash('error_message', $i18n->__('Cannot find the replay file. Please contact an administrator!') . $file);
                $referer = $request->getReferer();
                if ($referer) {
                    $this->redirect($referer);
                }
                else {
                    $this->redirect('@browsereplay');
                }
                
            }
        } else {
            $this->getUser()->setFlash('error_message', $i18n->__('Wrong request to download replay.'));
            $this->redirect('@homepage');
        }
    }

}