<?php

require_once dirname(__FILE__).'/../lib/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfPropelPlugin');
    $this->enablePlugins('sfGuardPlugin');
    $this->enablePlugins('sc2ReplayParserPlugin');
    $this->enablePlugins('sfThumbnailPlugin');
    $this->enablePlugins('sfFormExtraPlugin');
    $this->enablePlugins('sfPropelMigrationsLightPlugin');
    $this->enablePlugins('sfFeed2Plugin');
    $this->enablePlugins('sfFacebookConnectPlugin');
  }
}
