<?php use_helper('Date') ?>
<div style="margin-bottom:10px;">
    <div style="float:left; margin-left: 5px;">
        <h3><?php echo __('Standard information') ?></h3>
        <?php $region = $replay->getRegion() ?>
		<div class="shadow_box">
        <table class="stdtable replay_info" >
            <tr>
                <th><?php echo __('Map name') ?>:</th>
				<td><?php echo $replay->getMapName() ?></td>
            </tr>
            <tr>
                <th><?php echo __('Match length') ?>:</th>
				<td><?php echo ReplayPeer::getFormattedGameLength($replay->getGameLength()) ?></td>
            </tr>
            <tr>
                <th><?php echo __('Game speed') ?>:</th>
				<td><?php echo $replay->getGameSpeed() ?></td>
            </tr>
            <tr>
                <th><?php echo __('Average APM') ?>:</th>
				<td><?php echo $replay->getAvgAPM() ?></td>
            </tr>
            <tr>
                <th><?php echo __('Region')?>:</th>
                <td><?php echo $region ?> </td>
            </tr>
            <tr>
                <th><?php echo __('Date of play')?>:</th>
                <td><?php echo format_datetime($replay->getPlayDate()) ?> </td>
            </tr>
            <tr>
                <th><?php echo __('Description') ?>:</th>
				<td><?php echo $replay->getDescription() ?></td>
            </tr>
            <tr>
                <th><?php echo __('Category') ?>:</th>
				<td><?php echo $replay->getReplayCategory()->getName() ?></td>
            </tr>
            <tr>
                <th><?php echo __('Download counter') ?>:</th>
				<td><?php echo $replay->getDownloadCount() ?></td>
            </tr>
            <tr>
                <th><?php echo __('Uploaded at') ?>:</th>
				<td><?php echo $replay->getCreatedAt() ?> by <b><?php echo $replay->getsfGuardUser()->getUsername() ?></b></td>
            </tr>
        </table>
		</div>
		<div class="hover_block download_link">
			<a href="<?php echo url_for('@downloadreplay?id='.$replay->getId()) ?>"><?php echo __('Download replay') ?></a>
		</div>
		<div class="hover_block">
			<div class="facebook_block">
        		<iframe scrolling="no" frameborder="0" style="border: medium none ; overflow: hidden; width: 280px; height: 30px;" allowtransparency="true" src="http://www.facebook.com/plugins/like.php?href=<?php echo 'http://test.cupapp.com'.url_for('@viewreplay?id='.$replay->getId().'&name='.$replay) ?>&layout=standard&show_faces=yes&amp;width=450&action=like&font=arial&colorscheme=light"></iframe>
 			</div>
		</div>

    </div>
    <div style="float:right;width:200px;margin-right:5px;">
        <h3><?php echo __('Players') ?></h3>
            <?php $i = 1; ?>
            <?php foreach ($replay->getPlayersInfo() as $teamNum => $team) : ?>
			
			<div class="shadow_box team_box">
				<table class="team_table">
					<?php foreach ($team as $player): ?>
						<tr>
							<td class="player_color">
								 <div class="pColorBox" style="background:#<?php echo $player['color'] ?>;"></div>
							</td>
								<td class="player_name">
								<a target="_blank" href="<?php echo ReplayHelper::getProfileUrl($region, $player['name'], $player['uid'], $player['uidIndex']) ?>"><?php echo $player['name'] ?></a>
							</td>
							<td class="player_icon">
								<img class="player_icon" alt="<?php echo $player['race'] ?>" title="" src="<?php echo RacePeer::getSmallImageUrlByName($player['race']) ?>"/>
							</td>
							<td class="player_apm">
								<span title="<?php echo __('This players average APM was %%ss%%',array('%%ss%%' => $player['avg_apm'])) ?>" style="font-size:.8em;">(<?php echo $player['avg_apm'] ?>)</span>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
            </div>

            <?php if($i < count($replay->getPlayers())) : ?>
                <div style="text-align:center;font-weight: bold;">VS</div>
                <?php $i++; ?>
            <?php endif; ?>
            <?php endforeach; ?>
            <div style="height:10px;"></div>
        <h3 class="toggle_down" id="winner_box"><?php echo __('Winner?') ?> <span style="float:right;"><img src="/images/icons/toggledown.png"/></span></h3>
        <div id="winner_list" class="shadow_box">
        <?php if ($replay->isWinnerKnown()) : ?>
            <?php $i = 0; ?>
            <?php foreach($replay->getPlayersInfo() as $teamNum => $team) : ?>
            <?php foreach($team as $player): ?>
                <?php if ($player['winner']) : ?>
                    <?php if ($i == 0){ echo $player['name'];$i++;} else echo ', '.$player['name']; ?>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <?php echo __('Winner is unkown') ?>
        <?php endif; ?>
        </div>
    </div>
    <div style="clear:both;"></div>
</div>
