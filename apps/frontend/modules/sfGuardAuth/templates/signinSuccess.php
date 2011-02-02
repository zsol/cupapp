<?php slot('title'); ?>
    <?php echo __('Login') ?>
<?php end_slot(); ?>

<?php slot('leftbar'); ?>
    <?php include_partial('global/leftbar') ?>
<?php end_slot(); ?>

 <?php slot('rightbar'); ?>
    <?php include_partial('global/rightbar') ?>
<?php end_slot(); ?>

<div class="greybox">
    <h1 id="signin_title"><?php echo __('Login form') ?></h1>
    <form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
      <table>
        <?php echo $form ?>
      </table>

      <input type="submit" value="sign in" />
    </form>
<div style="clear:both;"></div>
</div>
