<?php

/**
 * ReplayOftheweek form base class.
 *
 * @method ReplayOftheweek getObject() Returns the current form's model object
 *
 * @package    Cupapp
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseReplayOftheweekForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'replay_id'   => new sfWidgetFormPropelChoice(array('model' => 'Replay', 'add_empty' => false)),
      'description' => new sfWidgetFormTextarea(),
      'created_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'replay_id'   => new sfValidatorPropelChoice(array('model' => 'Replay', 'column' => 'id')),
      'description' => new sfValidatorString(),
      'created_at'  => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('replay_oftheweek[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ReplayOftheweek';
  }


}
