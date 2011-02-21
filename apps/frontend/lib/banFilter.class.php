<?php

class banFilter extends sfFilter
{
  public function execute($filterChain)
  {
    if ($this->isFirstCall()) {
      $user = $this->getContext()->getUser();
      if ($user->isAuthenticated() && !$user->getGuardUser()->getIsActive()) {
        $user->signOut();
        // stop executing
      }
    }
    $filterChain->execute();
  }
}
        