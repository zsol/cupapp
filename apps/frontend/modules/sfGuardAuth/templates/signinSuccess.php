<?php slot('title'); ?>
    <?php echo __('Login') ?>
<?php end_slot(); ?>

<?php slot('leftbar'); ?>
    <?php include_partial('global/leftbar') ?>
<?php end_slot(); ?>

 <?php slot('rightbar'); ?>
    <?php include_partial('global/rightbar') ?>
<?php end_slot(); ?>

<?php
use_helper('sfFacebookConnect');
slot('fb_connect');
include_facebook_connect_script();
end_slot();
?>

<div class="greybox">
	<h1 id="signin_title"><?php echo __('Login form') ?></h1>
	<form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
  <div class="facebook_login">
    <?php echo facebook_connect_button(); ?>
  </div>
    	<table class='login_table'>
			<tr>
				<th><?php echo $form['username']->renderLabel(__('Username')) ?></th>
				<td><?php echo $form['username'] ?></td>
				<td class='error_field'><?php echo $form['username']->renderError() ?></td>
			</tr>
			<tr>
				<th><?php echo $form['password']->renderLabel(__('Password'))?></th>
				<td><?php echo $form['password'] ?></td>
				<td class='error_field'><?php echo $form['password']->renderError() ?></td>
			</tr>
			<tr>
				<th><?php echo $form['remember']->renderLabel(__('Remember me')) ?></th>
				<td><?php echo $form['remember'] ?></td>
				<td class='error_field'><?php echo $form['remember']->renderError() ?></td>
			</tr>
			<tr>
				<td colspan='2'><?php echo $form['_csrf_token'] ?></td>
			</tr>
		</table>
		<div class='login_input'>
			<input type="submit" value="<?php echo __('Sign in') ?>" />
		</div>
    </form>
	<div style="clear:both;"></div>
</div>
