<?php slot('title'); ?>
    <?php echo __('Home') ?>
<?php end_slot(); ?>

<?php slot('leftbar'); ?>
    <?php include_partial('global/leftbar') ?>
<?php end_slot(); ?>

<?php slot('rightbar'); ?>
    <?php include_partial('global/rightbar') ?>
<?php end_slot(); ?>

<div class="greybox">
    <h1 id="replay_index_title"><?php echo __('Home') ?></h1>
    <div id="index_div">
            <p>
                    Hello dear visitor! <br/><br/>
                    <?php echo __("What you see now are the beginnings of a replay sharing site. Please use it to your pleasure! If you find any bugs, sending the details to my email address (%%contact%%) would help a lot.", array('%%contact%%' => link_to(__("Contact"), 'replay/contact'))); ?> <br/><br/>
                    <?php echo __("Thanks in advance.")?> <br/>
                    Zsol <br/><br/>
            </p>
    </div>
<div style="clear:both;"></div>
</div>