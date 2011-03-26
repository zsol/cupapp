<?php slot('title'); ?>
   <?php echo __('Terms and Conditions'); ?>
<?php end_slot(); ?>

<?php slot('leftbar'); ?>
    <?php include_partial('global/leftbar') ?>
<?php end_slot(); ?>

<?php slot('rightbar'); ?>
    <?php include_partial('global/rightbar') ?>
<?php end_slot(); ?>

<div class="greybox">
  <h1 id="rules_title"><?php echo __('Terms and Conditions');?></h1>
  <?php echo __('This is a really simple T&C for the sake of human readability. By using the site, you agree to, first and foremost, act in good belief; use the site as it was intended to be used (share replays); not be a troll and be nice to other users of the site.'); ?>
  <hr />
  <?php echo __('If you see someone you think is not adhering to above rules, do not hesitate to %%s%%.', array('%%s%%' => '<a href="' . url_for('replay/contact') . '">' . __('contact the admins') . '</a>')); ?>
</div>