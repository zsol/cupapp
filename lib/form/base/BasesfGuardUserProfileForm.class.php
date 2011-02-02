<?php

/**
 * sfGuardUserProfile form base class.
 *
 * @method sfGuardUserProfile getObject() Returns the current form's model object
 *
 * @package    Cupapp
 * @subpackage form
 * @author     Your name here
 */
abstract class BasesfGuardUserProfileForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'user_id'        => new sfWidgetFormPropelChoice(array('model' => 'sfGuardUser', 'add_empty' => false)),
      'email'          => new sfWidgetFormInputText(),
      'avatar'         => new sfWidgetFormInputText(),
      'modified_at'    => new sfWidgetFormDateTime(),
      'last_commented' => new sfWidgetFormDateTime(),
      'last_uploaded'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'user_id'        => new sfValidatorPropelChoice(array('model' => 'sfGuardUser', 'column' => 'id')),
      'email'          => new sfValidatorString(array('max_length' => 50)),
      'avatar'         => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'modified_at'    => new sfValidatorDateTime(),
      'last_commented' => new sfValidatorDateTime(array('required' => false)),
      'last_uploaded'  => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_guard_user_profile[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfGuardUserProfile';
  }


}
