<?php

/**
 * ReplayOftheweek filter form base class.
 *
 * @package    Cupapp
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseReplayOftheweekFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'replay_id'   => new sfWidgetFormPropelChoice(array('model' => 'Replay', 'add_empty' => true)),
      'description' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'replay_id'   => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Replay', 'column' => 'id')),
      'description' => new sfValidatorPass(array('required' => false)),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('replay_oftheweek_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ReplayOftheweek';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'replay_id'   => 'ForeignKey',
      'description' => 'Text',
      'created_at'  => 'Date',
    );
  }
}
