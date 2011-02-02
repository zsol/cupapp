<?php


/**
 * Skeleton subclass for performing query and update operations on the 'sf_guard_user_profile' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * 07/14/10 22:11:30
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class sfGuardUserProfilePeer extends BasesfGuardUserProfilePeer {
    static public function retrieveByEmail ($email) {
        $c = new Criteria();
        $c->add(sfGuardUserProfilePeer::EMAIL, $email);

        return sfGuardUserProfilePeer::doSelectOne($c);
    }
} // sfGuardUserProfilePeer
