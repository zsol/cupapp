<?php slot('title'); ?>
    <?php echo __('Register') ?>
<?php end_slot(); ?>

<?php slot('leftbar'); ?>
    <?php include_partial('global/leftbar') ?>
<?php end_slot(); ?>

 <?php slot('rightbar'); ?>
    <?php include_partial('global/rightbar') ?>
<?php end_slot(); ?>

<div class="greybox">
    <h1><?php echo __('Register') ?></h1>
    <form action="<?php echo url_for('@register') ?>" method="post" enctype="multipart/form-data">
      <?php echo $form['_csrf_token'] ?>
      <table class="registerForm">
        <tr>
          <th><?php echo $form['username']->renderLabel(__('Username')) ?></th>
          <td>
              <?php echo $form['username']->renderError() ?>
              <?php echo $form['username'] ?>
          </td>
        </tr>
        <tr>
          <th><?php echo $form['password1']->renderLabel(__('Password')) ?></th>
          <td>
              <?php echo $form['password1']->renderError() ?>
              <?php echo $form['password1'] ?>
          </td>
        </tr>
        <tr>
          <th><?php echo $form['password2']->renderLabel(__('Password confirmation')) ?></th>
          <td>
              <?php echo $form['password2']->renderError() ?>
              <?php echo $form['password2'] ?>
          </td>
        </tr>
        <tr>
          <th><?php echo $form['email']->renderLabel(__('Email address')) ?></th>
          <td>
              <?php echo $form['email']->renderError() ?>
              <?php echo $form['email'] ?>
          </td>
        </tr>
        <tr>
          <th><?php echo $form['avatar']->renderLabel(__('Avatar (optional)')) ?></th>
          <td>
              <?php echo $form['avatar']->renderError() ?>
              <?php echo $form['avatar'] ?>
          </td>
        </tr>
        <tr>
          <th><?php echo $form['rules']->renderLabel(__('Accept our rules')) ?></th>
          <td>
              <?php echo $form['rules']->renderError() ?>
              <?php echo $form['rules']->render(array('style'=>'width:10px;')) ?> <a href="<?php echo url_for('@rules') ?>"><?php echo __('I accept the rules written here.') ?></a>
          </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:right;">
                    <input type="submit" value="<?php echo __('Register') ?>" />
            </td>
        </tr>
      </table>
    </form>
<div style="clear:both;"></div>
</div>
