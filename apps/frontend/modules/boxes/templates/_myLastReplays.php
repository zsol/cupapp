<div class="greybox">
    <h3><?php echo __('My replays') ?></h3>
    <?php if (count($replays) == 0) : ?>
        <?php echo __('You have no replays in the system.') ?>
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