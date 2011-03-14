<div class="greybox">
  <h1 id="replay_amend_title"><?php echo __('Amend replay')?></h1>
<div style="width:320px;margin:0px auto;">
  <form action="<?php echo url_for('@amendreplay?id='.$replay->getId()) ?>" method="POST" enctype="multipart/form-data">
  <table id="job_form">
    <tfoot>
      <tr>
        <td colspan="2" style="text-align:right;">
          <?php echo link_to(__('Delete replay'), 'replay/delete', array('method' => 'delete',
                                                                         'confirm' => __('Are you sure?'))) ?>
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
</div>
