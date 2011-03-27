<?php

/**
 * Migrations between versions 002 and 003.
 */
class Migration003 extends sfMigration
{
  /**
   * Migrate up to version 003.
   */
  public function up()
  {
    $this->executeSQL("ALTER TABLE replay_comment DROP FOREIGN KEY `replay_comment_FK_1`; ALTER TABLE replay_comment DROP FOREIGN KEY `replay_comment_FK_2`; ALTER TABLE replay_comment ADD CONSTRAINT `replay_comment_FK_1` FOREIGN KEY (`user_id`) REFERENCES `sf_guard_user` (`id`) ON DELETE CASCADE; ALTER TABLE replay_comment ADD CONSTRAINT `replay_comment_FK_2` FOREIGN KEY (`replay_id`) REFERENCES `replay` (`id`) ON DELETE CASCADE");
  }

  /**
   * Migrate down to version 002.
   */
  public function down()
  {
    $this->executeSQL("ALTER TABLE replay_comment DROP FOREIGN KEY `replay_comment_FK_1`; ALTER TABLE replay_comment DROP FOREIGN KEY `replay_comment_FK_2`; ALTER TABLE replay_comment ADD CONSTRAINT `replay_comment_FK_1` FOREIGN KEY (`user_id`) REFERENCES `sf_guard_user` (`id`); ALTER TABLE replay_comment ADD CONSTRAINT `replay_comment_FK_2` FOREIGN KEY (`replay_id`) REFERENCES `replay` (`id`)");    
  }
}
