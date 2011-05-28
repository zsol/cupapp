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
      $cat_name_max = sfConfig::get('app_category_name_max', 20);
      $cat_name_min = sfConfig::get('app_category_name_min', 4);

      $this->setValidator('avg_apm', new sfValidatorInteger(array('min' => 0, 'max' => 65535)));

      $this->setWidget('replay_file', new sfWidgetFormInputFile());
      $this->setValidator('replay_file', new sfValidatorFile(
              array('max_size' => sfConfig::get('app_replay_max_upload_size'),'required' => true)),
              array('max_size' => $i18n->__('File is too big (maximum %%size%% byte)',array('%%size%%' => sfConfig::get('app_replay_max_upload_size'))),
                    'required' => $i18n->__('This field is required!'))
      );

      $this->setValidator('description', new sfValidatorString(array('min_length' => sfConfig::get('app_replay_description_min'), 'max_length' => sfConfig::get('app_replay_description_max')),array(
                    'min_length' => $i18n->__('This description is too short! At least %%ss%% characters!', array('%%ss%%' => sfConfig::get('app_replay_description_min'))),
                    'max_length' => $i18n->__('This description is too big! Maximum %%ss%% characters!', array('%%ss%%' => sfConfig::get('app_replay_description_max'))),
                    'required' => $i18n->__('This field is required!')
      )));

      $this->setWidget('new_category_name', new sfWidgetFormInput());
      $this->setValidator('new_category_name', 
                          new sfValidatorString(array('max_length' => $cat_name_max,
                                                      'min_length' => $cat_name_min,
                                                      'required' => false),
                                                array('max_length' => $i18n->__('The category name is too long! At most %%c%% characters', array('%%c%%' => $cat_name_max)),
                                                      'min_length' => $i18n->__('The category name is too short! At least %%c%% characters', array('%%c%%' => $cat_name_min)))));;

      $this->useFields(array('replay_file','description','category_id', 'new_category_name'));
  }
}
