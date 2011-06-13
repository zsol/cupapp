<?php
class facebookRememberMeFilter extends sfFilter
{
  public function execute($filterChain)
  {
    if ($this->isFirstCall() && $this->context->getUser()->isAnonymous() && $this->context->getRequest()->getCookie('signed_out') != 'true')
    {
      $sfGuardUser = sfFacebook::getSfGuardUserByFacebookSession(false);
      if ($sfGuardUser)
      {
        $this->getContext()->getUser()->signIn($sfGuardUser, true);
      }
    }
    $filterChain->execute();
  }
}
