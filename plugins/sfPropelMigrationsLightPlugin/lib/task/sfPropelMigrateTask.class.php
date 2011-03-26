<?php

/*
 * This file is part of the sfPropelMigrationsLightPlugin package.
 * (c) 2006-2008 Martin Kreidenweis <sf@kreidenweis.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A symfony 1.1 port for the pake migrate task.
 *
 * @package     sfPropelMigrationsLightPlugin
 * @subpackage  task
 * @author      Martin Kreidenweis <sf@kreidenweis.com>
 * @version     SVN: $Id: sfPropelMigrateTask.class.php 19757 2009-06-30 21:03:05Z exien $
 */
class sfPropelMigrateTask extends sfPropelBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      //new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
      new sfCommandArgument('schema-version', sfCommandArgument::OPTIONAL, 'The target schema version'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));

    $this->aliases = array('migrate');
    $this->namespace = 'propel';
    $this->name = 'migrate';
    $this->briefDescription = 'Migrates the database schema to another version';
  }

  protected function execute($arguments = array(), $options = array())
  {
    $autoloader = sfSimpleAutoload::getInstance();
    $autoloader->addDirectory(sfConfig::get('sf_plugins_dir').'/sfPropelMigrationsLightPlugin/lib');

    $configuration = ProjectConfiguration::getApplicationConfiguration($options['application'], $options['env'], true);

    $databaseManager = new sfDatabaseManager($configuration);

    $migrator = new sfMigrator;

    if (isset($arguments['schema-version']) && ctype_digit($arguments['schema-version']))
    {
      $runMigrationsCount = $migrator->migrate((int) $arguments['schema-version']);
    }
    else
    {
      $runMigrationsCount = $migrator->migrate();
    }

    $currentVersion = $migrator->getCurrentVersion();

    $this->logSection('migrations', 'migrated '.$runMigrationsCount.' step(s)');
    $this->logSection('migrations', 'current database version: '.$currentVersion);
  }
}
