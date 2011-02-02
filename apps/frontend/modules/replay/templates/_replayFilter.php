<h3><?php echo __('Replay filter'); ?></h3>
<form action="" method="GET" id="filterform">
    <div class="greybox">
    <?php echo __('List me all the replays that has a player like %%seachPlayer%% take place on the map %%searchMap%% ', array('%%seachPlayer%%' => $form['search_player']->render(array('style' => 'width:150px;')), '%%searchMap%%' => $form['search_map']->render(array('style' => 'width:150px;')))) ?>
    <br/>
    <?php echo __('I am only interested in replays in the category of %%category%% and game type of %%gametype%%', array('%%category%%' => $form['category_id'], '%%gametype%%' => $form['game_type_id'])); ?>
    <br/>
    <?php echo __('Please order the list for me by %%orderby%%', array('%%orderby%%' => $form['order_options'])); ?>
    </div>
    <input style="float:right;color:red;" type="submit" value="<?php echo __('Search/Filter') ?>"/>
</form>
<div style="clear:both;height:5px;"></div>