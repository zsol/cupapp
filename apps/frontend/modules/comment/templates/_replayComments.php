<?php if (count($comments)) : ?>
    <?php use_javascript('comments.js') ?>
    <?php $i = 1; ?>
    <?php foreach ($comments as $comment) : ?>
        <?php $user = $comment->getSfGuardUser() ?>
        <div class="comment" id="comment_<?php echo $comment->getId() ?>">
            <div class="comment_header">
                <a name="comment_<?php echo $comment->getId() ?>" href="#comment_<?php echo $comment->getId() ?>">#<?php echo $i ?></a> <?php echo $user->getUsername()  ?>
	   <?php if ($sf_user->isAuthenticated() && $sf_user->isAllowedToComment()):?>
  	     - <a href="#comment_form" onclick="replyTo(<?php echo $i?>)"><?php echo __("Reply")?></a>
	     <?php if ($sf_user->isSuperAdmin()): ?>
	       - Delete
    	     <?php endif; ?>
	   <?php endif; ?>
	   <span style="float:right;"><?php echo $comment->getCreatedAt() ?></span>
            </div>
            <div class="comment_body">
                <div class="comment_avatar"><img style="float:left;" alt="avatar" src="<?php echo $user->getProfile()->getAvatarOrDefaultUrl() ?>"/></div>
		<div class="comment_content"><?php echo str_replace(array("\r\n", "\n", "\r"), "<br />", $comment->getComment()); ?></div>
            </div>
        </div>
    <?php $i++; ?>
    <?php endforeach; ?>
<?php else : ?>
    <?php echo __('No comments yet.') ?>
<?php endif; ?>