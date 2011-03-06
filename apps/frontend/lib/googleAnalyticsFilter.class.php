<?php

// based on http://snippets.symfony-project.org/snippet/396
class googleAnalyticsFilter extends sfFilter
{
  public function execute($filterChain)
  {
    // Nothing to do before the action
    $filterChain->execute();
    // Find google code and check if current module is not disabled
    if(($gaCode = sfConfig::get("app_google_analytics_code",false)) !== false
       && !in_array($this->getContext()->getModuleName(),sfConfig::get("app_google_analytics_disabled_modules",array()))) {
      //Decorate the response with the tracker code
      $response = $this->getContext()->getResponse();
      $response->setContent(str_ireplace('</body>', $gaCode.'</body>',$response->getContent())); 
    }
  }
}