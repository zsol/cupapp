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
        <?php echo __("UPDATE: The site should be able to parse replays made with the 1.3.3 patch. (No, this project didn't die, expect more features soon ;-))"); ?>
      </p>
            <p>
              <?php echo __("Hello dear visitor!") ?> <br/><br/>
              <?php echo __("What you see now are the beginnings of a replay sharing site. Please use it to your pleasure! If you find any bugs, sending the details to my email address (%%contact%%) would help a lot.", array('%%contact%%' => link_to(__("Contact"), '@contact'))); ?> <br/><br/>
              <?php echo __("Thanks in advance.")?> <br/>
              <hr />
              <span class="home_update"><?php echo __("Update:") ?></span>
              <?php echo __("You now can connect your facebook account to the site! If you already have a username and password here, just log in with those and then click on the facebook connect button. Once this is done, you will always be logged in if you are logged in to facebook.") ?> <br/>
              <?php echo __("If you don't have an account on this site yet, you can create one by clicking on %%REGISTER%%, and then choosing the facebook button. This will automatically connect with your facebook account and you won't have to remember any passwords", array('%%REGISTER%%' => __("Register"))); ?> <br/>
            </p>
    </div>
<div style="clear:both;"></div>
</div>