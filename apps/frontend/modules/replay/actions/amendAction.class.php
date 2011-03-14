<?php

class amendAction extends sfAction
{
  public function execute($request) {
    $i18n = sfContext::getInstance()->getI18N();

    $id = $request->getParameter('id', '');
    $this->forward404If($id == '', $i18n->__('Replay not given'));

    $replay = ReplayPeer::retrieveByPK($id);
    $this->forward404Unless($replay, $i18n->__('Replay not found'));

    if (!$replay->isAmendableBy($this->getUser()->getId())) {
      $this->getUser()->setFlash('error_message', $i18n->__('Sorry, this replay is not amendable.'));
      $this->forward('replay', 'view');
    }
    $form = new ReplayAmendForm($replay);

    $this->form = $form;
    $this->replay = $replay;

    if ($request->isMethod('post')) {
      $form->bind($request->getParameter($form->getName()));
      if ($form->isValid()) {
        $replay = $form->save();
       
        $this->redirect('@viewreplay?id='. $replay->getId().'&name='.$replay);
      }
    }
  }
}