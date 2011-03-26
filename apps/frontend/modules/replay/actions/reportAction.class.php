<?php
class reportAction extends sfAction
{
  public function execute($request) {
    $i18n = sfContext::getInstance()->getI18N();

    $id = $request->getParameter('id', '');
    $this->forward404If($id == '', $i18n->__('Replay not given'));

    $replay = ReplayPeer::retrieveByPK($id);
    $this->forward404Unless($replay, $i18n->__('Replay not found'));

    $replay->report();

    $this->redirect('@viewreplay?id='. $replay->getId() .'&name='. $replay);
  }
}