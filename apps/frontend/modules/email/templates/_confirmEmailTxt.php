<?php echo __('Dear') ?> <?php echo $username ?>!

<?php echo __('Please visit %%url%% to confirm your email address.', array('%%url%%' => url_for('@confirmemail?username='.$username.'&code='.$salt))); ?>

Sc2.Blizzfanatic.com