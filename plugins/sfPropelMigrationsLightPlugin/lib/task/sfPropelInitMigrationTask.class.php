<?php

/*
 * This file is part of the sfPropelMigrationsLightPlugin package.
 * (c) 2006-2008 Martin Kreidenweis <sf@kreidenweis.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A symfony 1.1 port for the pake init-migration task.
 * 
 * @package     sfPropelMigrationsLightPlugin
 * @subpackage  task
 * @author      Martin Kreidenweis <sf@kreidenweis.com>
 * @version     SVN: $Id: sfPropelInitMigrationTask.class.php 10225 2008-07-11 18:29:49Z Kris.Wallsmith $
 */
class sfPropelInitMigrationTask extends sfPropelBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The name of the migration'),
    ));

    $this->aliases = array('init-migration');
    $this->namespace = 'propel';
    $this->name = 'init-migration';
    $this->briefDescription = 'Creates a new migration class file';
  }

  protected function execute($arguments = array(), $options = array())
  {
    $autoloader = sfSimpleAutoload::getInstance();
    $autoloader->addDirectory(sfConfig::get('sf_plugins_dir').'/sfPropelMigrationsLightPlugin/lib');

    $migrator = new sfMigrator;

    if (!is_dir($migrator->getMigrationsDir()))
    {
      $this->getFilesystem()->mkDirs($migrator->getMigrationsDir());
    }

    $this->logSection('migrations', 'generating new migration stub');
    $filename = $migrator->generateMigration($arguments['name']);
    $this->logSection('file+', $filename);
  }
}
