<div class="greybox">
    <h3><img alt="comments" src="/images/icons/comments.png"/> <?php echo __('Latest comments') ?></h3>
    <?php if (count($comments) == 0) :?>
       <?php echo __('No comments yet. This is a rare moment, so appreciate it!') ?>
    <?php else: ?>
       <?php foreach($comments as $comment) : ?>
       <?php $replay = $comment->getReplay() ?>
       <div class="comment_list_box">
         <div class="comment_list_box_header">
             <div style="float:left;"><img style="float:left;margin:4px 3px 3px 0px;" alt="avatar" src="<?php echo $comment->getsfGuardUser()->getProfile()->getAvatarOrDefaultUrl(AvatarHelper::SIZE_SMALL) ?>"/></div>
             <div style="float:right;overflow:visible;margin:-1px 0px 0px 0px;padding:0px;">
                 <a href="<?php echo url_for('@viewreplay?id='.$replay->getId().'&name='.$replay.'#comment_'.$comment->getId()) ?>"><img alt="show comment" src="/images/icons/magnifier.png"/></a>
             </div>
             <div><?php echo $comment->getsfGuardUser()->getUserName() ?></b> @ <?php echo $comment->getCreatedAt() ?></div>
             <div><?php echo link_to(substr($replay,0,28).'..','@viewreplay?id='.$replay->getId().'&name='.$replay) ?></div>
         </div>

         <?php echo $comment->getCommentPrefix(150) ?>
       </div>
        <div style="clear:both;"></div>
       <?php endforeach;?>
    <?php endif; ?>
</div>