<?php

class registerForm extends sfForm
{

    public function configure()
    {
        $i18n = sfContext::getInstance()->getI18N();

        $this->setWidgets(array(
            'username'  => new sfWidgetFormInput(),
            'password1' => new sfWidgetFormInput(array('type' => 'password')),
            'password2' => new sfWidgetFormInput(array('type' => 'password')),
            'email'     => new sfWidgetFormInput(),
            'rules'     => new sfWidgetFormInputCheckbox(),
            'avatar'    => new sfWidgetFormInputFile(),
        ));

        $this->setValidators(array(
            'username' => new sfValidatorAnd(array(
                new userNameValidator(),
                new sfValidatorString(array(
                    'required' => true,
                    'min_length' => sfConfig::get('app_register_username_min_length'),
                    'max_length' => sfConfig::get('app_register_username_max_length')
                    ), array(
                        'required'   => $i18n->__('Please provide a username.'),
                        'min_length' => $i18n->__('The username should be at least %%ss%% characters long.', array('%%ss%%' => sfConfig::get('app_register_username_min_length'))),
                        'max_length' => $i18n->__('The username cannot be longer than %%ss%% characters.', array('%%ss%%' => sfConfig::get('app_register_username_max_length'))),
                )),
            ),array(),array('required' => $i18n->__('Please provide a username.'))),
            'password1' => new sfValidatorString(array(
                'required' => true,
                'min_length' => sfConfig::get('app_register_password_min_length'),
                'max_length' => sfConfig::get('app_register_password_max_length')
                ), array(
                    'required'   => $i18n->__('Please provide a password.'),
                    'min_length' => $i18n->__('Your password has to be at least %%ss%% characters long.', array('%%ss%%' => sfConfig::get('app_register_password_min_length'))),
                    'max_length' => $i18n->__('Your password cannot be longer than %%ss%% characters.', array('%%ss%%' => sfConfig::get('app_register_password_max_length'))),
            )),
            'password2' => new sfValidatorString(array(
                'required' => true,
            ), array(
                'required' => $i18n->__('Please confirm your password.')
            )),
            'email'     => new sfValidatorAnd(array(
                new sfValidatorEmail(array(
                    'required' => true
                    ), array(
                    'required' => $i18n->__('Please provide an email address.'),
                    'invalid'  => $i18n->__('Please provide a valid email address (me@example.com).'),
                )),
                new sfValidatorString(array(
                    'min_length' => sfConfig::get('app_register_email_min_length'),
                    'required'   => true),
                        array(
                            'min_length' => $i18n->__('Your email address should be at least %%ss%% characters long.', array('%%ss%%' => sfConfig::get('app_register_email_min_length'))),
                            'required'   => $i18n->__('Please provide an email address.'),
                )),
                new emailValidator()
            ),array(),array('required' => $i18n->__('Please provide an email address.'))),
                    
            'rules' => new sfValidatorBoolean(array('required' => true), array(
                'invalid'    => $i18n->__('Please accept the rules to register.'),
                'required'   => $i18n->__('Please accept the rules to register.'),
            )),
            'avatar' => new sfValidatorFile(
              array('max_size' => sfConfig::get('app_register_avatar_max_upload_size'),'required' => false),
              array('max_size' => $i18n->__('File is too big (maximum %%size%% byte)',array('%%size%%' => sfConfig::get('app_register_avatar_max_upload_size'))),
        ))));

        $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('password1', '==', 'password2',array(),array(
            'invalid' => $i18n->__('The confirmation password should match the original one.')
        )));
        $this->widgetSchema->setNameFormat('register[%s]');
    }

}