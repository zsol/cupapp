<?php

/**
 * Migrations between versions 005 and 006.
 */
class Migration006 extends sfMigration
{
  /**
   * Migrate up to version 006.
   */
  public function up()
  {
    $this->executeSQL("ALTER TABLE `replay` ADD COLUMN `storage_id` VARCHAR(40) NOT NULL");
    $replays = ReplayPeer::doSelect(new Criteria);
    foreach ($replays as $replay) {
      $storage_id = sha1($replay->getStoreDir() . rand());
      $this->executeSQL("UPDATE `replay` SET `storage_id`='".$storage_id."' WHERE `id`=".$replay->getId());
      $targetdir = sfConfig::get('sf_upload_dir') . '/replay/' . $replay->getUserId() . '/' . $storage_id;
      mkdir($targetdir);
      $this->moveDir(sfConfig::get('sf_upload_dir') . '/replay/' . $replay->getUserId() . '/' . strtotime($replay->getCreatedAt('Y-m-d H:i:s')), $targetdir);
    }
  }

  /**
   * Migrate down to version 005.
   */
  public function down()
  {
    $replays = ReplayPeer::doSelect(new Criteria);
    foreach ($replays as $replay) {
      $targetdir = sfConfig::get('sf_upload_dir') . '/replay/' . $replay->getUserId() . '/' . strtotime($replay->getCreatedAt('Y-m-d H:i:s'));
      mkdir($targetdir);
      $this->moveDir(sfConfig::get('sf_upload_dir') . '/replay/' . $replay->getUserId() . '/' . $replay->getStorageId(), $targetdir);
    }

    $this->executeSQL("ALTER TABLE `replay` DROP COLUMN `storage_id`");
        
  }

  private function moveDir($dir, $dest) {
    if (substr($dir, -1) == "/") {
      $dir = substr($dir, 0, -1);
    }
    
    $files = scandir($dir);
    foreach ($files as $file) {
      if ($file != '.' && $file != '..') {
        if (filetype($dir . '/' . $file) == "dir") {
          mkdir($dest . '/' . $file);
          $this->moveDir($dir . '/' . $file, $dest . '/' . $file);
        } else {
          rename($dir . '/' . $file, $dest . '/' . $file);
        }
      }
    }
    reset($files);
    rmdir($dir);
  }

}
