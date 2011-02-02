<h3><?php echo __('How to share this replay?') ?></h3>
<?php echo __('Send this link to your friends') ?>:<br/>
<div style="text-align:center;">
    <textarea style="width:400px;height:45px;">http://www.cupapp.com<?php echo url_for('@viewreplay?id='.$replay->getId().'&name='.$replay) ?></textarea>
</div>
