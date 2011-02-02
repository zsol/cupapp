<?php

class userNameValidator extends sfValidatorBase
{
    public function configure($options = array(), $messages = array())
    {
        $i18n = sfContext::getInstance()->getI18N();

        $this->addOption('username_field', 'username');
        $this->addOption('throw_global_error', false);

        $this->setMessage('invalid', $i18n->__('The username is already registered.'));
    }

    /**
     * @see sfValidatorBase
     */
    protected function doClean($username)
    {
        if ($username) {
            // user exists?
            if (sfGuardUserPeer::retrieveByUsername($username)) {
                if ($this->getOption('throw_global_error')) {
                    throw new sfValidatorError($this, 'invalid');
                }

                throw new sfValidatorErrorSchema($this, array(
                    new sfValidatorError($this, 'invalid'),
                ));
            }
        }
        
        return $username;
    }
}
