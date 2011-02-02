<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RacePeerclass
 *
 * @author Eshton
 */
class RacePeer {
    const TERRAN  = 1;
    const PROTOSS = 2;
    const ZERG    = 3;
    const RANDOM  = 4;

    const RACE_IMAGE_URL = '/images/races/';

    static public function getSmallImageUrlById ( $raceId ) {
        switch ($raceId) {
            case self::TERRAN  : { return self::RACE_IMAGE_URL.'terran_small.png'; }
            case self::PROTOSS : { return self::RACE_IMAGE_URL.'protoss_small.png'; }
            case self::ZERG    : { return self::RACE_IMAGE_URL.'zerg_small.png'; }
            case self::RANDOM  : { return self::RACE_IMAGE_URL.'random_small.png'; }
        }
    }

    static public function getSmallImageUrlByName ($raceName) {
        return self::getSmallImageUrlById(self::getRaceIdByName($raceName));
    }

    static public function getRaceIdByName ( $raceName ) {
        $raceName = strtolower($raceName);
        switch($raceName) {
            case 'protoss': return self::PROTOSS;
            case 'terran' : return self::TERRAN;
            case 'zerg'   : return self::ZERG;
            case 'random' : return self::RANDOM;
        }
    }
}
?>
