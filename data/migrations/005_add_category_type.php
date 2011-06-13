<?php

/**
 * Migrations between versions 004 and 005.
 */
class Migration005 extends sfMigration
{
  /**
   * Migrate up to version 005.
   */
  public function up()
  {
    $this->executeSQL("ALTER TABLE `replay_category` ADD COLUMN `type` VARCHAR(50) default 'common' NOT NULL");
  }

  /**
   * Migrate down to version 004.
   */
  public function down()
  {
    $this->executeSQL("ALTER TABLE `replay_category` DROP COLUMN `type`");
  }
}
