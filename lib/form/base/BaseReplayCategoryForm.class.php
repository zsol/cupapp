<?php

/**
 * ReplayCategory form base class.
 *
 * @method ReplayCategory getObject() Returns the current form's model object
 *
 * @package    Cupapp
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseReplayCategoryForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'   => new sfWidgetFormInputHidden(),
      'code' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'   => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'code' => new sfValidatorString(array('max_length' => 50)),
    ));

    $this->widgetSchema->setNameFormat('replay_category[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ReplayCategory';
  }

  public function getI18nModelName()
  {
    return 'ReplayCategoryI18n';
  }

  public function getI18nFormClass()
  {
    return 'ReplayCategoryI18nForm';
  }

}
