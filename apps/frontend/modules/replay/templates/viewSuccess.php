<?php slot('title'); ?>
    <?php echo __('View Replay').' - '.$replay->getReplayGameType()->getName().' - '.$replay->getPlayersName(', ') ?>
<?php end_slot(); ?>

<?php slot('leftbar'); ?>
    <?php include_partial('global/leftbar') ?>
<?php end_slot(); ?>

 <?php slot('rightbar'); ?>
    <?php include_partial('global/rightbar') ?>
<?php end_slot(); ?>

<div class="greybox">
    <h1 id="replay_details_title"><?php echo __('Replay details') ?></h1>
    <?php include_partial('replay/replayDetails', array('replay' => $replay)) ?>
    <?php include_partial('replay/messageLog', array('replay' => $replay)) ?>
    <?php include_partial('replay/shareBox', array('replay' => $replay)) ?>
    <a class="top_link" href="#top"><?php echo __('#top') ?></a>
<div style="clear:both;"></div>
</div>

<div class="greybox">
    <h2><?php echo __('Replay comments') ?></h2>
    <?php include_component('comment','replayComments', array('replay' => $replay)) ?>
    <?php include_partial('comment/commentForm', array('form' => new ReplayCommentForm(), 'replay' => $replay)) ?>
    <a class="top_link" href="#top"><?php echo __('#top') ?></a>
<div style="clear:both;"></div>
</div>
