<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProfileHelper
 *
 * @author Eshton
 */
class AvatarHelper {

    const SIZE_NORMAL  = 1;
    const SIZE_MEDIUM  = 2;
    const SIZE_SMALL   = 3;

    public static function getName($size) {
        switch ($size) {
            case self::SIZE_NORMAL: return 'normal';
            case self::SIZE_MEDIUM: return 'medium';
            case self::SIZE_SMALL : return 'small';
        }
    }

    public static function getSizeWidth($size) {
        switch ($size) {
            case self::SIZE_NORMAL: return sfConfig::get('app_avatar_width_normal', 70);break;
            case self::SIZE_MEDIUM: return sfConfig::get('app_avatar_width_medium', 50);break;
            case self::SIZE_SMALL : return sfConfig::get('app_avatar_width_small', 20);break;
        }
    }

    public static function getSizeHeight($size) {
        switch ($size) {
            case self::SIZE_NORMAL: return sfConfig::get('app_avatar_height_normal', 70);break;
            case self::SIZE_MEDIUM: return sfConfig::get('app_avatar_small_medium', 50);break;
            case self::SIZE_SMALL : return sfConfig::get('app_avatar_small_small', 20);break;
        }
    }

    public static function createAvatarImages($filePath, sfGuardUserProfile $profile) {
        self::createAvatarImage($filePath, $profile, AvatarHelper::SIZE_NORMAL);
        self::createAvatarImage($filePath, $profile, AvatarHelper::SIZE_MEDIUM);
        self::createAvatarImage($filePath, $profile, AvatarHelper::SIZE_SMALL);
    }

    public static function createAvatarImage($filePath, sfGuardUserProfile $profile, $size) {
        ImageHelper::createImage(
                $filePath,
                $profile->getAvatarPath($size),
                self::getSizeWidth($size),
                self::getSizeHeight($size),
                sfConfig::get('app_avatar_image_format_mime_type', 'image/png'));
    }

}