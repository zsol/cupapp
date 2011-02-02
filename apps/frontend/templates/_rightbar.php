<?php include_partial('boxes/loginBox') ?>
<?php include_component('boxes','lastReplays') ?>

<?php if ($sf_user->isAuthenticated()) : ?>
    <?php include_component('boxes','myLastReplays', array('onlymine' => true)) ?>
<?php endif; ?>

<?php //include_partial('boxes/commentsBox') ?>