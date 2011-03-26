<?php

/*
 * This file is part of the sfPropelMigrationsLightPlugin package.
 * (c) 2006-2008 Martin Kreidenweis <sf@kreidenweis.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Symfony 1.0 tasks for sfPropelMigrationsLightPlugin.
 * 
 * @package    symfony
 * @subpackage plugin
 * @author     Martin Kreidenweis <sf@kreidenweis.com>
 * @version    SVN: $Id: sfPakePropelMigrationsLight.php 10226 2008-07-11 18:38:16Z Kris.Wallsmith $
 */

pake_desc('creates a new migration class file');
pake_task('init-migration', 'project_exists');

pake_desc('migrates the database schema to another version');
pake_task('migrate', 'project_exists');

function run_init_migration($task, $args)
{
  if (count($args) == 0)
  {
    throw new Exception('You must provide a migration name.');
  }

  if ($args[0])
  {
    $migrator = new sfMigrator();

    if (!is_dir($migrator->getMigrationsDir()))
    {
      pake_mkdirs($migrator->getMigrationsDir());
    }

    pake_echo_action('migrations', 'generating new migration stub');
    $filename = $migrator->generateMigration($args[0]);
    pake_echo_action('file+', $filename);
  }
}

function run_migrate($task, $args)
{
  if (count($args) == 0)
  {
    throw new Exception('You must provide a app.');
  }

  @list($app, $env) = explode(':', $args[0]);

  if (!is_dir(sfConfig::get('sf_app_dir').DIRECTORY_SEPARATOR.$app))
  {
    throw new Exception('The app "'.$app.'" does not exist.');
  }

  // define constants
  define('SF_ROOT_DIR',    sfConfig::get('sf_root_dir'));
  define('SF_APP',         $app);
  define('SF_ENVIRONMENT', $env ? $env : 'cli');
  define('SF_DEBUG',       true);

  // get configuration
  require_once SF_ROOT_DIR.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.SF_APP.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php';

  $databaseManager = new sfDatabaseManager();
  $databaseManager->initialize();

  $migrator = new sfMigrator();

  // if no other arguments besides app, then migrate to latest version
  if (count($args) == 1) 
  {
    $runMigrationsCount = $migrator->migrate();
  }
  elseif (isset($args[1]) && ctype_digit($args[1]))
  {
    $runMigrationsCount = $migrator->migrate($args[1]);
  }
  else
  {
    throw new Exception('You can provide a destination migration number as a second parameter');
  }

  $currentVersion = $migrator->getCurrentVersion();

  pake_echo_action('migrations', 'migrated '.$runMigrationsCount.' step(s)');
  pake_echo_action('migrations', 'current database version: '.$currentVersion);
}
