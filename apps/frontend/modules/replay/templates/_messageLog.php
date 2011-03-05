<?php $replay = $sf_data->getRaw('replay') ?>
<div id="message_log" style="margin-left: 5px; margin-right: 5px;">
    <h3 style="margin-bottom:2px;" class="toggle_down" id="message_log_header">
		<?php echo __('Message log') ?> <span style="float:right;"><img src="/images/icons/toggledown.png"/></span>
	</h3>
    <?php $messages = $replay->getMessageLog() ?>

    <div class="shadow_box" id="message_log_messages" style="display:none;">
    <table style="width:100%;" class="stdtable replay_messages">
        <tr>
            <th><?php echo __('Time')?></th>
            <th><?php echo __('Player') ?></th>
            <th><?php echo __('Target') ?></th>
            <th><?php echo __('Message') ?></th>
        </tr>
        <?php $i = 0; ?>
        <?php foreach($messages as $message) : ?>
        <tr>
            <td class="message_time"><?php echo $message['time'] ?></td>
            <td class="message_player"><?php echo $message['name'] ?></td>
            <td class="message_target"><?php echo $message['target'] ?></td>
            <td><?php echo $message['message'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    </div>
    <br/>
</div>
