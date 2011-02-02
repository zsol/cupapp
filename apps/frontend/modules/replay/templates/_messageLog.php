<?php $replay = $sf_data->getRaw('replay') ?>
<div id="message_log" style="width:100%;">
    <h3 style="margin-bottom:2px;" class="toggle_down" id="message_log_header"><?php echo __('Message log') ?> <span style="float:right;"><img src="/images/icons/toggledown.png"/></span></h3>
    <?php $messages = $replay->getMessageLog() ?>

    <div class="stdtable_border" id="message_log_messages" style="display:none;">
    <table style="width:100%;" class="stdtable replay_messages">
        <tr>
            <th><?php echo __('Time')?></th>
            <th><?php echo __('Player') ?></th>
            <th><?php echo __('Target') ?></th>
            <th><?php echo __('Message') ?></th>
        </tr>
        <?php $i = 0; ?>
        <?php foreach($messages as $message) : ?>
        <?php
            if ($i == 0) { $odd = 'normal'; $i++; }
            else { $odd = 'odd'; $i = 0; }
        ?>
        <tr class="<?php echo $odd ?>">
            <td><?php echo $message['time'] ?></td>
            <td><?php echo $message['name'] ?></td>
            <td><?php echo $message['target'] ?></td>
            <td><?php echo $message['message'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    </div>
    <br/>
</div>