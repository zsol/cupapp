<?php slot('title'); ?>
    <?php echo __('Replay index') ?>
<?php end_slot(); ?>

<?php slot('leftbar'); ?>
    <?php include_partial('global/leftbar') ?>
<?php end_slot(); ?>

 <?php slot('rightbar'); ?>
    <?php include_partial('global/rightbar') ?>
<?php end_slot(); ?>

<div class="greybox">
    <h1 id="replay_index_title"><?php echo __('Replay index') ?></h1>
    <?php include_partial('replay/indexContent') ?>
<div style="clear:both;"></div>
</div>