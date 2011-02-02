<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReplayHelper
 *
 * @author agoston
 */
class ReplayHelper {
    static public function getProfileUrl($name, $uid, $uidIndex) {
        if (empty($name) || empty($uid) || empty($uidIndex)) {
            return null;
        }

        $pattern = sfConfig::get('app_replay_profile_url_pattern');
        $pattern = str_replace('%%NAME%%', $name, $pattern);
        $pattern = str_replace('%%UID%%', $uid, $pattern);
        $pattern = str_replace('%%UIDINDEX%%', $uidIndex, $pattern);

        return $pattern;
    }
}
