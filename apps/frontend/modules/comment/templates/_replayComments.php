<?php if (count($comments)) : ?>
    <?php $i = 1; ?>
    <?php foreach ($comments as $comment) : ?>
        <?php $user = $comment->getSfGuardUser() ?>
        <div class="comment" id="comment_<?php echo $comment->getId() ?>">
            <div class="comment_header">
                <a name="comment_<?php echo $comment->getId() ?>" href="#comment_<?php echo $comment->getId() ?>">#<?php echo $i ?></a> <?php echo $user->getUsername()  ?> - Reply - Delete
                <span style="float:right;"><?php echo $comment->getCreatedAt() ?></span>
            </div>
            <div class="comment_body">
                <div class="comment_avatar"><img style="float:left;" alt="avatar" src="<?php echo $user->getProfile()->getAvatarOrDefaultUrl() ?>"/></div>
                <div class="comment_content"><?php echo $comment->getComment(); ?></div>
            </div>
        </div>
    <?php $i++; ?>
    <?php endforeach; ?>
<?php else : ?>
    <?php echo __('No comments yet.') ?>
<?php endif; ?>