<?php echo __('Dear %%username%%', array('%%username%%' => $username)) ?>

<?php echo __('Please visit %%url%% to confirm your email address.', array('%%url%%' => url_for('@confirmemail?username='.$username.'&code='.$salt))); ?>


CupApp