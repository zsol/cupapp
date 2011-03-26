<?php

/**
 * Migrations between versions 001 and 002.
 */
class Migration002 extends sfMigration
{
  /**
   * Migrate up to version 002.
   */
  public function up()
  {
    $this->executeSQL("ALTER TABLE replay ADD COLUMN `reported_count` SMALLINT(3) UNSIGNED default 0");
  }

  /**
   * Migrate down to version 001.
   */
  public function down()
  {
    $this->executeSQL("ALTER TABLE replay DROP COLUMN `reported_count`");
  }
}
