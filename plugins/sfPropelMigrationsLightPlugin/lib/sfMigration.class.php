<?php

/*
 * This file is part of the sfPropelMigrationsLightPlugin package.
 * (c) 2006-2008 Martin Kreidenweis <sf@kreidenweis.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Base class for all migrations.
 * 
 * @package    symfony
 * @subpackage plugin
 * @author     Martin Kreidenweis <sf@kreidenweis.com>
 * @version    SVN: $Id: sfMigration.class.php 20466 2009-07-24 15:21:32Z ntoniazzi $
 */
abstract class sfMigration
{
  protected
    $migrator        = null,
    $migrationNumber = null;

  /**
   * Constructor.
   * 
   * @param sfMigrator $migrator        The migrator instance calling this migration
   * @param integer    $migrationNumber The DB version the migration (up) will migrate to
   */
  public function __construct(sfMigrator $migrator, $migrationNumber)
  {
    $this->migrator = $migrator;
    $this->migrationNumber = $migrationNumber;
  }

  /**
   * Get the migrator instance that called this migration.
   * 
   * @return sfMigrator
   */
  public function getMigrator()
  {
    return $this->migrator;
  }

  /**
   * Get the Propel connection.
   * 
   * @return mixed
   */
  protected function getConnection()
  {
    return Propel::getConnection();
  }

  /**
   * Get the migration number of this migration.
   * 
   * @param   boolean $formatted If true the result is a zero-padded string, otherwise an integer is returned
   * 
   * @return  mixed
   */
  protected function getMigrationNumber($formatted = true)
  {
    return $formatted ? sprintf('%03d', $this->migrationNumber) : (int) $this->migrationNumber;
  }

  /**
   * Execute a SQL statement.
   * 
   * @param   string $sql the SQL code to execute
   * 
   * @return  integer Number of affected rows
   */
  protected function executeSQL($sql)
  {
    return sfMigrator::executeUpdate($sql);
  }

  /**
   * Execute a SQL query.
   * 
   * @param   string $sql The SQL statement.
   * @param   integer $fetchmode
   * 
   * @return  mixed
   */
  protected function executeQuery($sql, $fetchmode = null)
  {
    return sfMigrator::executeQuery($sql, $fetchmode);
  }

  /**
   * Add a column to an existing table.
   *
   * @param  string  $table   the table name
   * @param  string  $column  the name of the column to add
   * @param  string  $type    the type of the column to add
   * @param  bool    $notNull if the column should be NOT NULL
   * @param  mixed   $default default value for the column
   */
  protected function addColumn($table, $column, $type, $notNull = false, $default = null)
  {
    $sql = sprintf('ALTER TABLE %s ADD COLUMN %s %s', $table, $column, $type);

    if ($notNull)
    {
      $sql .= ' NOT NULL';
    }

    if (!is_null($default))
    {
      if (!ctype_digit($default))
      {
        $default = '"'.$default.'"';
      }

      $sql .= ' DEFAULT '.$default;
    }

    return $this->executeSQL($sql);
  }

  /**
   * Loads the fixture files of the migration.
   * 
   * Has to be called manually.
   * 
   * Be careful. Due to the nature Propel and fixture-loading works you'll 
   * probably get problems when you change the definitions of affected tables 
   * in later migrations.
   * 
   * @param boolean $deleteOldRecords Whether the affected tables' content should be deleted prior to loading the fixtures, default: false
   * @param string  $con Propel connection identifier, as defined in the database.yml file
   */
  protected function loadFixtures($deleteOldRecords = false, $con = 'symfony')
  {
    $fixturesDir = $this->getMigrator()->getMigrationsFixturesDir().DIRECTORY_SEPARATOR.$this->getMigrationNumber();

    if (!is_dir($fixturesDir))
    {
      throw new sfException('No fixtures exist for migration '.$this->getMigrationNumber());
    }

    $data = new sfPropelData();
    $data->setDeleteCurrentData($deleteOldRecords);
    $data->loadData($fixturesDir, $con);
  }

  /**
   * Execute SQL from a file.
   * 
   * @param   string $file Path to the SQL file
   */
  protected function loadSql($file)
  {
    if (!is_readable($file))
    {
      throw new sfException(sprintf('The SQL file %s does not exist or is not readable.', $file));
    }

    foreach (explode(';', file_get_contents($file)) as $query)
    {
      if (trim($query))
      {
        $this->executeQuery($query);
      }
    }
  }

  /**
   * Begin a transaction.
   */
  protected function begin()
  {
    $con = $this->getConnection();

    $con instanceof PropelPDO ? $con->beginTransaction() : $con->begin();
  }

  /**
   * Commit a transaction.
   */
  protected function commit()
  {
    $this->getConnection()->commit();
  }

  /**
   * Rollback a transaction.
   */
  protected function rollback()
  {
    $this->getConnection()->rollback();
  }

  /**
   * Output some diagnostic or informational message.
   * 
   * @param   string $text
   */
  protected function diag($text)
  {
    // something a little more sophisticated might be better...
    echo $text."\n";
  }

  /**
   * Migrate the schema up, from the previous version to the current one.
   */
  abstract public function up();

  /**
   * Migrate the schema down to the previous version, i.e. undo the modifications made in up()
   */
  abstract public function down();
}
