<?php slot('title'); ?>
    <?php echo __('Browse replays') ?>
<?php end_slot(); ?>

<?php slot('leftbar'); ?>
    <?php include_partial('boxes/loginBox') ?>
    <?php include_partial('global/leftbar') ?>
<?php end_slot(); ?>

<div class="greybox" style="width:834px;">
    <h1 id="replay_browse_title"><?php echo __('Browse replays') ?></h1>
    <?php include_partial('replay/replayFilter', array('form' => $form)) ?>
    <?php include_partial('replay/replayList', array('pager' => $pager)) ?>
<div style="clear:both;"></div>
</div>
