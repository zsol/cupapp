<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<?php slot('title'); ?>
    <?php echo __('Upload replay') ?>
<?php end_slot(); ?>

<?php slot('leftbar'); ?>
    <?php include_partial('global/leftbar') ?>
<?php end_slot(); ?>

 <?php slot('rightbar'); ?>
    <?php include_partial('global/rightbar') ?>
<?php end_slot(); ?>

<div class="greybox">
    <h1 id="replay_upload_title"><?php echo __('Upload replay') ?></h1>
    <?php if (!sfConfig::get('app_replay_disable_upload')) : ?>
        <?php include_partial('replay/uploadForm', array('form' => $form)) ?>
    <?php else: ?>
        <div style="text-align:center;"><?php echo __('Sorry the replay upload is currently turned off because of the new version of Starcraft2. Thank you for your understanding.') ?></div>
    <?php endif; ?>
<div style="clear:both;"></div>
</div>