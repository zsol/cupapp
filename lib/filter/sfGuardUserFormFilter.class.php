<?php

/**
 * sfGuardUser filter form.
 *
 * @package    Cupapp
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfGuardUserFormFilter.class.php 12896 2008-11-10 19:02:34Z fabien $
 */
class sfGuardUserFormFilter extends BasesfGuardUserFormFilter
{
  public function configure()
  {
    unset($this['algorithm'], $this['salt'], $this['password']);

    $this->widgetSchema['sf_guard_user_group_list']->setLabel('Groups');
    $this->widgetSchema['sf_guard_user_permission_list']->setLabel('Permissions');

    $this->widgetSchema['created_at'] = new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormJQueryDate(), 'to_date' => new sfWidgetFormJQueryDate()));
    $this->widgetSchema['last_login'] = new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormJQueryDate(), 'to_date' => new sfWidgetFormJQueryDate()));

  }
}
