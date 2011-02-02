<?php echo __('Dear') ?> <?php echo $username ?>!<br/>
<br/>
<?php echo __('Please visit <a href="%%url%%">%%url%%</a> to confirm your email address.', array('%%url%%' => 'http://'.sfConfig::get('app_domain').url_for('@confirmemail?username='.$username.'&code='.$salt))); ?><br/>
<br/>
Sc2.Blizzfanatic.com