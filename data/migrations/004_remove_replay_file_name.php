<?php

/**
 * Migrations between versions 003 and 004.
 */
class Migration004 extends sfMigration
{
  /**
   * Migrate up to version 004.
   */
  public function up()
  {
    $this->executeSQL("ALTER TABLE replay DROP COLUMN `file_name`");
  }

  /**
   * Migrate down to version 003.
   */
  public function down()
  {
    $this->executeSQL("ALTER TABLE replay ADD COLUMN `file_name` VARCHAR(255)");
    $this->executeSQL("UPDATE replay set file_name=CONCAT(UNIX_TIMESTAMP(created_at), '.SC2Replay')");
    $this->executeSQL("ALTER TABLE replay MODIFY `file_name` VARCHAR(255) NOT NULL");
  }
}
