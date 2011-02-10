<?php echo __('Dear %%username%%', array('%%username%%' => $username)) ?>
<br/>
<?php echo __('Please visit <a href="%%url%%">%%url%%</a> to confirm your email address.', array('%%url%%' => 'http://'.sfConfig::get('app_domain').url_for('@confirmemail?username='.$username.'&code='.$salt))); ?><br/>
<br/>
CupApp