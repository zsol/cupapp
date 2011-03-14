<?php

class ReplayAmendForm extends BaseReplayForm
{
  public function configure() {
    $i18n = sfContext::getInstance()->getI18N();

    $descr_min_length = sfConfig::get('app_replay_description_min_length', 10);

    $this->setValidator('description', 
                        new sfValidatorString(array('min_length' => $descr_min_length),
                                              array('min_length' => $i18n->__('Too short! At least %%ss%% characters!', array('%%ss%%' => $descr_min_length)),
                                                    'required' => $i18n->__('This field is required!'))));
    $this->useFields(array('description', 'category_id'));
  }
}