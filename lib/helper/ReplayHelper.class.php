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
    static private function translateRegionToURL($region) {
      $region = strtolower($region);
      if ($region == "sg")
	return "sea";
      return $region;
    }

    static public function getProfileUrl($region, $name, $uid, $uidIndex) {
        if (empty($name) || empty($uid) || empty($uidIndex) || empty($region)) {
            return null;
        }

        $pattern = sfConfig::get('app_replay_profile_url_pattern');
        $pattern = str_replace('%%NAME%%', $name, $pattern);
        $pattern = str_replace('%%UID%%', $uid, $pattern);
        $pattern = str_replace('%%UIDINDEX%%', $uidIndex, $pattern);
	$pattern = str_replace('%%REGION%%', ReplayHelper::translateRegionToURL($region), $pattern);

        return $pattern;
    }

}
