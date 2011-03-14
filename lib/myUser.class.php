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
      return $this->getGuardUser() ? $this->getGuardUser()->getId() : false;
    }

    public function getAvatar() {
      return $this->getGuardUser() ? $this->getGuardUser()->getProfile()->getAvatar() : false;
    }

    public function getAvatarUrl() {
      return $this->getGuardUser() ? $this->getGuardUser()->getProfile()->getAvatarUrl() : false;
    }

    public function getAvatarOrDefaultUrl() {
      return $this->getGuardUser() ? $this->getGuardUser()->getProfile()->getAvatarOrDefaultUrl() : false;
    }

    public function getUsername() {
      return $this->getGuardUser() ? $this->getGuardUser()->getUsername() : false;
    }

    public function getEmail() {
      return $this->getGuardUser() ? $this->getGuardUser()->getProfile()->getEmail() : false;
    }

    public function isAllowedToComment() {
        if (!$this->getGuardUser()) { return false; }
        $flood_gap = sfConfig::get('app_comment_flood_defence_seconds');
        $now = time();
        $last_commented = strtotime($this->getGuardUser()->getProfile()->getLastCommented());

        if (($now - $last_commented) > $flood_gap) {
            return true;
        }

        return false;
    }

    public function isAllowedToUpload() {
        if (!$this->getGuardUser()) { return false; }
        $flood_gap = sfConfig::get('app_replay_flood_defence_seconds');
        $now = time();
        $last_uploaded = strtotime($this->getGuardUser()->getProfile()->getLastUploaded());

        if (($now - $last_uploaded) > $flood_gap) {
            return true;
        }

        return false;
    }
}
