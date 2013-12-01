<div id="ui-widget" style="position: fixed; left: 20px; float:left; z-index: 10">
	<?php while(notificationsExist()){
	?>
	<div class="ui-state-highlight ui-corner-all" style="margin-bottom: 10px ; display: none">
		<p>
			<?php $nf = getNotification(); if($nf ->type == 'info'): ?>
			<span class="ui-icon ui-icon-info"></span>
			<?php elseif($nf ->type == 'error'): ?>
			<span class="ui-icon ui-icon-error"></span>
			<?php endif; ?>
			<?php echo $nf-> message; ?>
		</p>
	</div>
	<?php } ?>
</div>