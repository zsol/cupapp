<div class="margined_div">
	<h3 style="margin-bottom: 10px;"><?php echo __('How to share this replay?') ?></h3>
	<div class="margined_div">
		<p style="margin-bottom: 10px;"><?php echo __('Send this link to your friends') ?>:</p>
		<div class=margined_div" style="text-align:center; padding-bottom: 10px;">
			<textarea style="width:400px;height:45px;"><?php echo url_for('@viewreplay?id='.$replay->getId().'&name='.$replay, true /*absolute url*/) ?></textarea>
		</div>
	</div>
</div>
