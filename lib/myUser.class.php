<?php

class myUser extends sfGuardSecurityUser
{
    protected $guardUser;

    public function getGuardUser() {
        if (!$this->guardUser) {
            $this->guardUser = parent::getGuardUser();
        }

        return $this->guardUser;
    }

    public function getId() {
        return $this->getGuardUser()->getId();
    }

    public function getAvatar() {
        return $this->getGuardUser()->getProfile()->getAvatar();
    }

    public function getAvatarUrl() {
        return $this->getGuardUser()->getProfile()->getAvatarUrl();
    }

    public function getAvatarOrDefaultUrl() {
        return $this->getGuardUser()->getProfile()->getAvatarOrDefaultUrl();
    }

    public function getUsername() {
        return $this->getGuardUser()->getUsername();
    }

    public function getEmail() {
        return $this->getGuardUser()->getProfile()->getEmail();
    }

    public function isAllowedToComment() {
        $flood_gap = sfConfig::get('app_comment_flood_defence_seconds');
        $now = time();
        $last_commented = strtotime($this->getGuardUser()->getProfile()->getLastCommented());

        if (($now - $last_commented) > $flood_gap) {
            return true;
        }

        return false;
    }

    public function isAllowedToUpload() {
        $flood_gap = sfConfig::get('app_replay_flood_defence_seconds');
        $now = time();
        $last_uploaded = strtotime($this->getGuardUser()->getProfile()->getLastUploaded());

        if (($now - $last_uploaded) > $flood_gap) {
            return true;
        }

        return false;
    }
}
