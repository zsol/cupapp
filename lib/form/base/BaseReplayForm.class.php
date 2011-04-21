<?php

/**
 * Replay form base class.
 *
 * @method Replay getObject() Returns the current form's model object
 *
 * @package    Cupapp
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseReplayForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'user_id'        => new sfWidgetFormPropelChoice(array('model' => 'sfGuardUser', 'add_empty' => false)),
      'game_type_id'   => new sfWidgetFormPropelChoice(array('model' => 'ReplayGameType', 'add_empty' => false)),
      'category_id'    => new sfWidgetFormPropelChoice(array('model' => 'ReplayCategory', 'add_empty' => false)),
      'game_info'      => new sfWidgetFormTextarea(),
      'description'    => new sfWidgetFormTextarea(),
      'avg_apm'        => new sfWidgetFormInputText(),
      'players'        => new sfWidgetFormInputText(),
      'map_name'       => new sfWidgetFormInputText(),
      'download_count' => new sfWidgetFormInputText(),
      'published_at'   => new sfWidgetFormDateTime(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
      'reported_count' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'user_id'        => new sfValidatorPropelChoice(array('model' => 'sfGuardUser', 'column' => 'id')),
      'game_type_id'   => new sfValidatorPropelChoice(array('model' => 'ReplayGameType', 'column' => 'id')),
      'category_id'    => new sfValidatorPropelChoice(array('model' => 'ReplayCategory', 'column' => 'id')),
      'game_info'      => new sfValidatorString(),
      'description'    => new sfValidatorString(),
      'avg_apm'        => new sfValidatorInteger(array('min' => -32768, 'max' => 32767)),
      'players'        => new sfValidatorString(array('max_length' => 255)),
      'map_name'       => new sfValidatorString(array('max_length' => 255)),
      'download_count' => new sfValidatorInteger(array('min' => -32768, 'max' => 32767)),
      'published_at'   => new sfValidatorDateTime(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(array('required' => false)),
      'updated_at'     => new sfValidatorDateTime(array('required' => false)),
      'reported_count' => new sfValidatorInteger(array('min' => -32768, 'max' => 32767, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('replay[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Replay';
  }


}
