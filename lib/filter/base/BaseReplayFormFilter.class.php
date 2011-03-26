<?php

/**
 * Replay filter form base class.
 *
 * @package    Cupapp
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseReplayFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'        => new sfWidgetFormPropelChoice(array('model' => 'sfGuardUser', 'add_empty' => true)),
      'game_type_id'   => new sfWidgetFormPropelChoice(array('model' => 'ReplayGameType', 'add_empty' => true)),
      'category_id'    => new sfWidgetFormPropelChoice(array('model' => 'ReplayCategory', 'add_empty' => true)),
      'file_name'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'game_info'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'avg_apm'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'players'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'map_name'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'download_count' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'published_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'reported_count' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_id'        => new sfValidatorPropelChoice(array('required' => false, 'model' => 'sfGuardUser', 'column' => 'id')),
      'game_type_id'   => new sfValidatorPropelChoice(array('required' => false, 'model' => 'ReplayGameType', 'column' => 'id')),
      'category_id'    => new sfValidatorPropelChoice(array('required' => false, 'model' => 'ReplayCategory', 'column' => 'id')),
      'file_name'      => new sfValidatorPass(array('required' => false)),
      'game_info'      => new sfValidatorPass(array('required' => false)),
      'description'    => new sfValidatorPass(array('required' => false)),
      'avg_apm'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'players'        => new sfValidatorPass(array('required' => false)),
      'map_name'       => new sfValidatorPass(array('required' => false)),
      'download_count' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'published_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'reported_count' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('replay_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Replay';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'user_id'        => 'ForeignKey',
      'game_type_id'   => 'ForeignKey',
      'category_id'    => 'ForeignKey',
      'file_name'      => 'Text',
      'game_info'      => 'Text',
      'description'    => 'Text',
      'avg_apm'        => 'Number',
      'players'        => 'Text',
      'map_name'       => 'Text',
      'download_count' => 'Number',
      'published_at'   => 'Date',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
      'reported_count' => 'Number',
    );
  }
}
