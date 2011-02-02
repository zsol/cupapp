<?php

/**
 * Replay form.
 *
 * @package    Cupapp
 * @subpackage form
 * @author     Your name here
 */
class ReplayUploadForm extends BaseReplayForm
{
  public function configure()
  {
      $i18n = sfContext::getInstance()->getI18N();

      $this->setValidator('avg_apm', new sfValidatorInteger(array('min' => 0, 'max' => 65535)));

      $this->setWidget('replay_file', new sfWidgetFormInputFile());
      $this->setValidator('replay_file', new sfValidatorFile(
              array('max_size' => sfConfig::get('app_replay_max_upload_size'),'required' => true)),
              array('max_size' => $i18n->__('File is too big (maximum %%size%% byte)',array('%%size%%' => sfConfig::get('app_replay_max_upload_size'))),
                    'required' => $i18n->__('This field is required!'))
      );

      $this->setValidator('description', new sfValidatorString(array('min_length' => 10),array(
                    'min_length' => $i18n->__('Too short! At least %%ss%% characters!', array('%%ss%%' => sfConfig::get('app_replay_description_min'))),
                    'required' => $i18n->__('This field is required!')
      )));

      $this->useFields(array('replay_file','description','category_id'));
  }
}
