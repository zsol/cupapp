<?php

/*
 * This file is part of the sfPropelMigrationsLightPlugin package.
 * (c) 2006-2008 Martin Kreidenweis <sf@kreidenweis.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Manage all calls to the sfMigration class instances.
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Martin Kreidenweis <sf@kreidenweis.com>
 * @version    SVN: $Id: sfMigrator.class.php 21782 2009-09-08 12:49:32Z Kris.Wallsmith $
 */
class sfMigrator
{
  /**
   * Migration filenames.
   *
   * @var array $migrations
   */
  protected $migrations = array();

  /**
   * Perform an update on the database.
   *
   * @param   string $sql
   *
   * @return  integer
   */
  static public function executeUpdate($sql)
  {
    $con = Propel::getConnection();

    return $con instanceof PropelPDO ? $con->exec($sql) : $con->executeUpdate($sql);
  }

  /**
   * Perform a query on the database.
   *
   * @param   string $sql
   * @param   string $fetchmode
   *
   * @return  mixed
   */
  static public function executeQuery($sql, $fetchmode = null)
  {
    $con = Propel::getConnection();

    if ($con instanceof PropelPDO)
    {
      $stmt = $con->prepare($sql);
      $stmt->execute();

      return $stmt;
    }
    else
    {
      return $con->executeQuery($sql, $fetchmode);
    }
  }

  /**
   * Constructor.
   */
  public function __construct()
  {
    $this->loadMigrations();
  }

  /**
   * Execute migrations.
   *
   * @param   integer $destVersion  Version number to migrate to, defaults to
   *                                the max existing
   *
   * @return  integer Number of executed migrations
   */
  public function migrate($destVersion = null)
  {
    $maxVersion = $this->getMaxVersion();
    if ($destVersion === null)
    {
      $destVersion = $maxVersion;
    }
    else
    {
      $destVersion = (int) $destVersion;

      if (($destVersion > $maxVersion) || ($destVersion < 0))
      {
        throw new sfException(sprintf('Migration %d does not exist.', $destVersion));
      }
    }

    $sourceVersion = $this->getCurrentVersion();

    // do appropriate stuff according to migration direction
    if ($destVersion == $sourceVersion)
    {
      return 0;
    }
    elseif ($destVersion < $sourceVersion)
    {
      $res = $this->migrateDown($sourceVersion, $destVersion);
    }
    else
    {
      $res = $this->migrateUp($sourceVersion, $destVersion);
    }

    return $res;
  }

  /**
   * Generate a new migration stub
   *
   * @param   string $name Name of the new migration
   *
   * @return  string Filename of the new migration file
   */
  public function generateMigration($name)
  {
    // calculate version number for new migration
    $maxVersion = sprintf('%03d', $this->getMaxVersion());
    $newVersion = sprintf('%03d', $maxVersion + 1);

    // sanitize name
    $name = preg_replace('/[^a-zA-Z0-9]/', '_', $name);

    $upLogic = '';
    $downLogic = '';

    if ('001' == $newVersion)
    {
      $this->generateFirstMigrationLogic($name, $newVersion, $upLogic, $downLogic);
    }

    $newClass = <<<EOF
<?php

/**
 * Migrations between versions $maxVersion and $newVersion.
 */
class Migration$newVersion extends sfMigration
{
  /**
   * Migrate up to version $newVersion.
   */
  public function up()
  {
    $upLogic
  }

  /**
   * Migrate down to version $maxVersion.
   */
  public function down()
  {
    $downLogic
  }
}

EOF;

    // write new migration stub
    $newFileName = $this->getMigrationsDir().DIRECTORY_SEPARATOR.$newVersion.'_'.$name.'.php';
    file_put_contents($newFileName, $newClass);

    return $newFileName;
  }

  /**
   * Get the list of migration filenames.
   *
   * @return array
   */
  public function getMigrations()
  {
    return $this->migrations;
  }

  /**
   * @return integer The lowest migration that exists
   */
  public function getMinVersion()
  {
    return $this->migrations ? $this->getMigrationNumberFromFile($this->migrations[0]) : 0;
  }

  /**
   * @return integer The highest existing migration that exists
   */
  public function getMaxVersion()
  {
    $count = count($this->migrations);

    return $count ? $this->getMigrationNumberFromFile($this->migrations[$count - 1]) : 0;
  }

  /**
   * Get the current schema version from the database.
   *
   * If no schema version is currently stored in the database, one is created
   * and initialized with 0.
   *
   * @return integer
   */
  public function getCurrentVersion()
  {
    try
    {
      $result = $this->executeQuery('SELECT version FROM schema_info');
      if ($result instanceof PDOStatement)
      {
        $currentVersion = $result->fetchColumn(0);
      }
      else
      {
        if ($result->next())
        {
          $currentVersion = $result->getInt('version');
        }
        else
        {
          throw new sfDatabaseException('Unable to retrieve current schema version.');
        }
      }
    }
    catch (Exception $e)
    {
      // assume no schema_info table exists yet so we create it
      $this->executeUpdate('CREATE TABLE schema_info (version INTEGER)');

      // and insert the version record as 0
      $this->executeUpdate('INSERT INTO schema_info (version) VALUES (0)');
      $currentVersion = 0;
    }

    return $currentVersion;
  }

  /**
   * Get the number encoded in the given migration file name.
   *
   * @param   string $file The filename to look at
   *
   * @return  integer
   */
  public function getMigrationNumberFromFile($file)
  {
    $number = substr(basename($file), 0, 3);

    if (!ctype_digit($number))
    {
      throw new sfParseException('Migration filename could not be parsed.');
    }

    return $number;
  }

  /**
   * Get the directory where migration classes are saved.
   *
   * @return  string
   */
  public function getMigrationsDir()
  {
    return sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'migrations';
  }

  /**
   * Get the directory where migration fixtures are saved.
   *
   * @return  string
   */
  public function getMigrationsFixturesDir()
  {
    return $this->getMigrationsDir().DIRECTORY_SEPARATOR.'fixtures';
  }

  /**
   * Write the given version as current version to the database.
   *
   * @param integer $version New current version
   */
  protected function setCurrentVersion($version)
  {
    $version = (int) $version;

    $this->executeUpdate("UPDATE schema_info SET version = $version");
  }

  /**
   * Migrate down, from version $from to version $to.
   *
   * @param   integer $from
   * @param   integer $to
   *
   * @return  integer Number of executed migrations
   */
  protected function migrateDown($from, $to)
  {
    $con = Propel::getConnection();
    $counter = 0;

    // iterate over all needed migrations
    for ($i = $from; $i > $to; $i--)
    {
      try
      {
        $con instanceof PropelPDO ? $con->beginTransaction() : $con->begin();

        $migration = $this->getMigrationObject($i);
        $migration->down();

        $this->setCurrentVersion($i-1);

        $con->commit();
      }
      catch (Exception $e)
      {
        $con->rollback();
        throw $e;
      }

      $counter++;
    }

    return $counter;
  }

  /**
   * Migrate up, from version $from to version $to.
   *
   * @param   integer $from
   * @param   integer $to
   * @return  integer Number of executed migrations
   */
  protected function migrateUp($from, $to)
  {
    $con = Propel::getConnection();
    $counter = 0;

    // iterate over all needed migrations
    for ($i = $from + 1; $i <= $to; $i++)
    {
      try
      {
        $con instanceof PropelPDO ? $con->beginTransaction() : $con->begin();

        $migration = $this->getMigrationObject($i);
        $migration->up();

        $this->setCurrentVersion($i);

        $con->commit();
      }
      catch (Exception $e)
      {
        $con->rollback();
        throw $e;
      }

      $counter++;
    }

    return $counter;
  }

  /**
   * Get the migration object for the given version.
   *
   * @param   integer $version
   *
   * @return  sfMigration
   */
  protected function getMigrationObject($version)
  {
    $file = $this->getMigrationFileName($version);

    // load the migration class
    require_once $file;
    $migrationClass = 'Migration'.$this->getMigrationNumberFromFile($file);

    return new $migrationClass($this, $version);
  }

  /**
   * Version to filename.
   *
   * @param   integer $version
   *
   * @return  string Filename
   */
  protected function getMigrationFileName($version)
  {
    return $this->migrations[$version-1];
  }

  /**
   * Load all migration file names.
   */
  protected function loadMigrations()
  {
    $this->migrations = sfFinder::type('file')->name('/^\d{3}.*\.php$/')->maxdepth(0)->in($this->getMigrationsDir());

    sort($this->migrations);

    if (count($this->migrations) > 0)
    {
      $minVersion = $this->getMinVersion();
      $maxVersion = $this->getMaxVersion();

      if (1 != $minVersion)
      {
        throw new sfInitializationException('First migration is not migration 1. Some migration files may be missing.');
      }

      if (($maxVersion - $minVersion + 1) != count($this->migrations))
      {
        throw new sfInitializationException('Migration count unexpected. Migration files may be missing. Migration numbers must be unique.');
      }
    }
  }

  /**
   * Auto generate logic for the first migration.
   *
   * @param   string $name
   * @param   string $newVersion
   * @param   string $upLogic
   * @param   string $downLogic
   */
  protected function generateFirstMigrationLogic($name, $newVersion, & $upLogic, & $downLogic)
  {
    $sqlFiles = sfFinder::type('file')->name('*.sql')->in(sfConfig::get('sf_root_dir').'/data/sql');
    if ($sqlFiles)
    {
      // use propel sql files for the up logic
      $sql = '';
      foreach ($sqlFiles as $sqlFile)
      {
        $sql .= file_get_contents($sqlFile);
      }
      file_put_contents($this->getMigrationsDir().DIRECTORY_SEPARATOR.$newVersion.'_'.$name.'.sql', $sql);
      $upLogic .= sprintf('$this->loadSql(dirname(__FILE__).\'/%s_%s.sql\');', $newVersion, $name);

      // drop tables for down logic
      $downLines = array();

      // disable mysql foreign key checks
      if (false !== $fkChecks = strpos($sql, 'FOREIGN_KEY_CHECKS'))
      {
        $downLines[] = '$this->executeSQL(\'SET FOREIGN_KEY_CHECKS=0\');';
        $downLines[] = '';
      }

      preg_match_all('/DROP TABLE IF EXISTS `(\w+)`;/', $sql, $matches);
      foreach ($matches[1] as $match)
      {
        $downLines[] = sprintf('$this->executeSQL(\'DROP TABLE %s\');', $match);
      }

      // enable mysql foreign key checks
      if (false !== $fkChecks)
      {
        $downLines[] = '';
        $downLines[] = '$this->executeSQL(\'SET FOREIGN_KEY_CHECKS=1\');';
      }

      $downLogic .= join("\n    ", $downLines);
    }
  }
}
