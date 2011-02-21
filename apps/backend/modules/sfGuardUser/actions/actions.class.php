<?php

class sfGuardUserActions extends autoSfGuardUserActions
{
  public function executeListBan(sfWebRequest $request)
  {
    $user = $this->getRoute()->getObject();
    $user->setIsActive(false);
    $user->save();
    $this->getUser()->setFlash('notice', $user->getUsername() . " is now banned.");
    $this->redirect('@sf_guard_user');
  }

  public function executeBatchBan(sfWebRequest $request)
  {
    $ids = $request->getParameter("ids");
    $users = sfGuardUserPeer::retrieveByPks($ids);
    foreach ($users as $user)
    {
      $user->setIsActive(false);
      $user->save();
    }
    $this->getUser()->setFlash('notice', count($users) . " users are now banned.");
    $this->redirect('@sf_guard_user');
  }
}