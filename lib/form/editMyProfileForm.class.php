<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of editMyProfileForm
 *
 * @author Eshton
 */
class editMyProfileForm extends registerForm {
    public function configure() {
        $i18n = sfContext::getInstance()->getI18N();

        $this->setWidgets(array(
            'password1' => new sfWidgetFormInput(array('type' => 'password')),
            'password2' => new sfWidgetFormInput(array('type' => 'password')),
            'avatar'    => new sfWidgetFormInputFile(),
        ));

        $this->setValidators(array(
            'password1' => new sfValidatorString(array(
                'required' => false,
                'min_length' => sfConfig::get('app_register_password_min_length'),
                'max_length' => sfConfig::get('app_register_password_max_length')
                ), array(
                    'required'   => $i18n->__('Please provide a password.'),
                    'min_length' => $i18n->__('Your password has to be at least %%ss%% characters long.', array('%%ss%%' => sfConfig::get('app_register_password_min_length'))),
                    'max_length' => $i18n->__('Your password cannot be longer than %%ss%% characters.', array('%%ss%%' => sfConfig::get('app_register_password_max_length'))),
            )),
            'password2' => new sfValidatorString(array(
                'required' => false,
            ), array(
                'required' => $i18n->__('Please confirm your password.')
            )),
            'avatar' => new sfValidatorFile(
              array('max_size' => sfConfig::get('app_register_avatar_max_upload_size'),'required' => false),
              array('max_size' => $i18n->__('File is too big (maximum %%size%% byte)',array('%%size%%' => sfConfig::get('app_register_avatar_max_upload_size'))),
        ))));

        $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('password1', '==', 'password2',array(),array(
            'invalid' => $i18n->__('The confirmation password should match the original one.')
        )));
        $this->widgetSchema->setNameFormat('editprofile[%s]');
    }
}