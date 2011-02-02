<?php

/**
 * ReplayCategory filter form base class.
 *
 * @package    Cupapp
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseReplayCategoryFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'code' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'code' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('replay_category_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ReplayCategory';
  }

  public function getFields()
  {
    return array(
      'id'   => 'Number',
      'code' => 'Text',
    );
  }
}
