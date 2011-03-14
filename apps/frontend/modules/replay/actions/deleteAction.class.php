<?php

class deleteAction extends sfAction
{
  public function execute($request) {
    try {
      $request->checkCSRFProtection();
    } catch (Exception $e) {
      $this->logMessage("CSRF attempt from " . $request->getRemoteAddress(). ", referer: ". $request->getReferer(), 'crit');
      $this->redirect('@browsereplay');
    }

    $i18n = sfContext::getInstance()->getI18N();

    $id = $request->getParameter('id', '');
    $this->forward404If($id == '', $i18n->__('Replay not given'));

    $replay = ReplayPeer::retrieveByPK($id);
    $this->forward404Unless($replay, $i18n->__('Replay not found'));

    if (!$replay->isAmendableBy($this->getUser()->getId())) {
      $this->getUser()->setFlash('error_message', $i18n->__('Sorry, you are not allowed to delete this replay.'));
      $this->forward('replay', 'view');
    }
    
    $replay->delete();

    $this->redirect('@browsereplay');
  }
}