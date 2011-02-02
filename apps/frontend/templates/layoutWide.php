<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title>BlizzFanatic.com - <?php include_slot('title'); ?></title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
      <a name="top"></a>
      <div class="wrapper">
          <?php include_partial('global/menu') ?>
          <?php if ($sf_user->hasFlash('error_message')) : ?>
          <div class="top_err_message"><?php echo html_entity_decode($sf_user->getFlash('error_message')) ?></div>
          <?php endif;?>
          <?php if ($sf_user->hasFlash('success_message')) : ?>
            <div class="top_suc_message"><?php echo html_entity_decode($sf_user->getFlash('success_message')) ?></div>
          <?php endif;?>

          <?php if (has_slot('leftbar')) : ?>
            <div class="leftbar">
                <?php include_slot('leftbar') ?>
            </div>
          <?php endif; ?>

            <div class="main_content content_wide">
                <?php echo $sf_content ?>
            </div>

          <div style="clear:both;"></div>
      </div>
      <?php include_partial('global/footer') ?>
  </body>
</html>
