<?php if($sf_user->isAllowedToUpload()) : ?>
<div style="width:320px;margin:0px auto;">
<form action="<?php echo url_for('replay/upload') ?>" method="POST" enctype="multipart/form-data">
  <table id="job_form">
    <tfoot>
      <tr>
        <td colspan="2" style="text-align:right;">
          <input type="submit" value="<?php echo __('Upload replay') ?>" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form ?>
    </tbody>
  </table>
</form>
</div>
<?php else: ?>
    <?php echo __('Sorry you cannot upload a new replay right now. Please wait %%min%% minutes.', array('%%min%%' => sfConfig::get('app_replay_flood_defence_seconds') / 60)) ?>
<?php endif; ?>