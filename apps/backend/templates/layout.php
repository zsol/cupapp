<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
      <div class="admin_logo">Admin</div>
      <div class="menu">
          <a href="<?php echo url_for('sfGuardUser/index') ?>"><?php echo __('User admin') ?></a>
          <a href="<?php echo url_for('@replay_comment') ?>"><?php echo __('Comment admin') ?></a>
          <a href="<?php echo url_for('sfGuardGroup/index') ?>"><?php echo __('Group admin') ?></a>
          <a href="<?php echo url_for('sfGuardPermission/index') ?>"><?php echo __('Permission admin') ?></a>
          <a href="<?php echo url_for('@replay_replayadmin') ?>"><?php echo __('Replay admin') ?></a>
          <a href="/"><?php echo __('Back to frontend') ?></a>
      </div>
      <div class="admin_wrapper">
        <?php echo $sf_content ?>
        <div style="clear:both;"></div>
      </div>
  </body>
</html>
