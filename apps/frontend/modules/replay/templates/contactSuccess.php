<?php slot('title'); ?>
    <?php echo __('Contact') ?>
<?php end_slot(); ?>

<?php slot('leftbar'); ?>
    <?php include_partial('global/leftbar') ?>
<?php end_slot(); ?>

<?php slot('rightbar'); ?>
    <?php include_partial('global/rightbar') ?>
<?php end_slot(); ?>

<div class="greybox">
    <h1 id="contact_title"><?php echo __('Contact') ?></h1>
    <?php include_partial('replay/contactContent') ?>
<div style="clear:both;"></div>
</div>