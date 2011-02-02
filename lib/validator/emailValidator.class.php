<?php

class emailValidator extends sfValidatorBase
{
    public function configure($options = array(), $messages = array())
    {
        $i18n = sfContext::getInstance()->getI18N();

        $this->addOption('email_field', 'email');
        $this->addOption('throw_global_error', false);

        $this->setMessage('invalid', $i18n->__('This email address is already registered.'));
    }

    /**
     * @see sfValidatorBase
     */
    protected function doClean($email)
    {
        if ($email) {
            // user exists?
            if (sfGuardUserProfilePeer::retrieveByEmail($email)) {
                if ($this->getOption('throw_global_error')) {
                    throw new sfValidatorError($this, 'invalid');
                }

                throw new sfValidatorErrorSchema($this, array(
                    new sfValidatorError($this, 'invalid'),
                ));
            }
        }

        return $email;
    }
}