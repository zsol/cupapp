<?php

/**
 * Migrations between versions 006 and 007.
 */
class Migration007 extends sfMigration
{
  /**
   * Migrate up to version 007.
   */
  public function up()
  {
    $this->executeSQL("ALTER TABLE sf_guard_user_profile ADD COLUMN `facebook_uid` VARCHAR(20)");
    $this->executeSQL("ALTER TABLE sf_guard_user_profile ADD UNIQUE KEY `facebook_uid_index` (`facebook_uid`)");
    $this->executeSQL("ALTER TABLE sf_guard_user_profile ADD COLUMN `email_hash` VARCHAR(255)");
    $this->executeSQL("ALTER TABLE sf_guard_user_profile ADD UNIQUE KEY `email_hash_index` (`email_hash`)");
    $this->executeSQL("ALTER TABLE sf_guard_user_profile ADD UNIQUE KEY `email_index` (`email`)");
  }

  /**
   * Migrate down to version 006.
   */
  public function down()
  {
    $this->executeSQL("ALTER TABLE sf_guard_user_profile DROP INDEX `facebook_uid_index`");
    $this->executeSQL("ALTER TABLE sf_guard_user_profile DROP INDEX `email_index`");
    $this->executeSQL("ALTER TABLE sf_guard_user_profile DROP INDEX `email_hash_index`");
    $this->executeSQL("ALTER TABLE sf_guard_user_profile DROP COLUMN `facebook_uid`");
    $this->executeSQL("ALTER TABLE sf_guard_user_profile DROP COLUMN `email_hash`");
  }
}
