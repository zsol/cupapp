<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RegisterHelper
 *
 * @author Eshton
 */
class RegisterHelper {
    static public function createThumbnail($filePath, $savePath) {
        $thumbnail = new sfThumbnail(sfConfig::get('app_register_avatar_width'), sfConfig::get('app_register_avatar_width'));
        $thumbnail->loadFile($filePath);
        $thumbnail->save($savePath, 'image/jpeg');
    }
}