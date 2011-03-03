<div class="greybox">
    <h3><?php echo __('Latest comments') ?></h3>
    <?php if (count($comments) == 0) :?>
       <?php echo __('No comments yet. This is a rare moment, so appreciate it!') ?>
    <?php else: ?>
       <?php foreach($comments as $comment) : ?>
       <?php $replay = $comment->getReplay() ?>
       <div class="comment_list_box">
         <?php echo link_to($comment->getsfGuardUser()->getUserName(). ': ' .$comment->getCommentPrefix(22), '@viewreplay?id='.$replay->getId().'&name='.$replay.'#comment_'.$comment->getId()) ?>
         <span class="comment_list_box_time"><?php echo $replay->getCreatedAt() ?></span>
       </div>
       <?php endforeach;?>
    <?php endif; ?>
</div>