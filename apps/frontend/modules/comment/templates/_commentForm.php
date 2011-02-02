<h3 style="margin-bottom:2px;">Post a comment</h3>
<?php if ($sf_user->isAuthenticated() && $sf_user->isAllowedToComment()) : ?>
    <a name="comment_form"></a>
    <div>
    <form action="<?php echo url_for('comment/comment') ?>" method="POST" enctype="multipart/form-data">
      <table id="comment_form">
        <tfoot>
          <tr>
            <td colspan="2" style="text-align:right;">
              <input type="submit" value="<?php echo __('Send comment') ?>" />
            </td>
          </tr>
        </tfoot>
        <tbody>
            <tr>
                <td>
              <?php echo $form['comment'] ?>
              <?php echo $form['_csrf_token'] ?>
              <input type="hidden" name="replay_comment[replay_id]" id="replay_comment_replay_id" value="<?php echo $replay->getId() ?>">
                </td>
            </tr>
        </tbody>
      </table>
    </form>
    </div>
<?php else: ?>
    <?php if (!$sf_user->isAuthenticated()) : ?>
    <div>
        <?php echo __('You need to <a href="%s">log in</a> to comment.', array('%s' => url_for('@sf_guard_signin'))) ?>
    </div>
    <?php else: ?>
    <div>
        <?php echo __('You can only comment in every %%sec%% seconds.', array('%%sec%%' => sfConfig::get('app_comment_flood_defence_seconds'))) ?>
    </div>
    <?php endif; ?>
<?php endif; ?>