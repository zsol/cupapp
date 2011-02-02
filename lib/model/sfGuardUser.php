<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGuardUser.php 7634 2008-02-27 18:01:40Z fabien $
 */
class sfGuardUser extends PluginsfGuardUser
{

    protected $profile;

    public function sendValidationEmail($html = '', $text = '')
    {
        $message = sfContext::getInstance()->getMailer()->compose();
        $message->setSubject(sfContext::getInstance()->getI18N()->__('BlizzFanatic.Com Validation email'));
        $message->setTo($this->getProfile()->getEmail());
        $message->setFrom(sfConfig::get('app_noresponse_email'));

        $message->setBody($html, 'text/html');
        $message->addPart($text, 'text/plain');

        sfContext::getInstance()->getMailer()->send($message);
    }

    public function getAvatarSaveName()
    {
        return $this->getId() . '_' . time() . '.jpg';
    }

    public function getAvatarSavePath()
    {
        return sfConfig::get('sf_upload_dir').'/userimg/'.$this->getAvatarSaveName();
    }

    public function getProfile()
    {
        if (!$this->profile) {
          $this->profile = parent::getProfile();
        }

        return $this->profile;
    }
}
