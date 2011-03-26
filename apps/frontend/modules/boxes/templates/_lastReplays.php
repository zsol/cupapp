<div class="greybox">
    <h3><img alt="playbutton" src="/images/icons/control_play.png"/> <?php echo __('Last uploaded replays') ?></h3>
    <?php if (count($replays) == 0) : ?>
        <?php echo __('There is no replay in the system right now.') ?>
    <?php else : ?>
        <?php foreach($replays as $replay) : ?>
        <div class="repListBox">
            <a href="<?php echo url_for('@viewreplay?id='.$replay->getId().'&name='.$replay) ?>">
                    <?php echo $replay->getReplayGameType()->getName() ?> - <?php echo $replay->getMapName() ?> - <?php echo $replay->getPlayersName(', ') ?>
            </a>
            <span class="repListBoxTime"><?php echo $replay->getCreatedAt() ?></span>
            <div style="clear:both;"></div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>