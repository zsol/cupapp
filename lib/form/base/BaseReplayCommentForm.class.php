<?php

/**
 * ReplayComment form base class.
 *
 * @method ReplayComment getObject() Returns the current form's model object
 *
 * @package    Cupapp
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseReplayCommentForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'user_id'    => new sfWidgetFormPropelChoice(array('model' => 'sfGuardUser', 'add_empty' => false)),
      'replay_id'  => new sfWidgetFormPropelChoice(array('model' => 'Replay', 'add_empty' => false)),
      'culture'    => new sfWidgetFormInputText(),
      'comment'    => new sfWidgetFormTextarea(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'user_id'    => new sfValidatorPropelChoice(array('model' => 'sfGuardUser', 'column' => 'id')),
      'replay_id'  => new sfValidatorPropelChoice(array('model' => 'Replay', 'column' => 'id')),
      'culture'    => new sfValidatorString(array('max_length' => 7)),
      'comment'    => new sfValidatorString(),
      'created_at' => new sfValidatorDateTime(array('required' => false)),
      'updated_at' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('replay_comment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ReplayComment';
  }


}
