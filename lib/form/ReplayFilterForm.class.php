<?php

/**
 * Replay form.
 *
 * @package    Cupapp
 * @subpackage form
 * @author     Your name here
 */
class ReplayFilterForm extends BaseFormPropel
{

    public function setup() {

        $i18n = sfContext::getInstance()->getI18N();

        $this->setWidgets(array(
            'search_player' => new sfWidgetFormInput(),
            'search_map' => new sfWidgetFormInput(),
            'game_type_id' => new sfWidgetFormPropelChoice(array('model' => 'ReplayGameType', 'add_empty' => true)),
            'category_id' => new sfWidgetFormPropelChoice(array('model' => 'ReplayCategory', 'add_empty' => true)),
            'order_options' => new sfWidgetFormChoice(array('choices' => array('upload_date' => $i18n->__('upload date'), 'avg_apm' => $i18n->__('average APM')))),
        ));

        /* $this->setValidators(array(
          'game_type_id'   => new sfValidatorPropelChoice(array('model' => 'ReplayGameType', 'column' => 'id')),
          'category_id'    => new sfValidatorPropelChoice(array('model' => 'ReplayCategory', 'column' => 'id')),
          )); */

        $this->widgetSchema->setNameFormat('filter[%s]');

        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

        parent::setup();
    }

    public function getModelName() {
        return 'Replay';
    }

}
