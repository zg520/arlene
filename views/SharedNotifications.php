<div id="ui-widget" style="position: fixed; left: 20px; float:left; z-index: 10">
	<?php while(notificationsExist()){
	?>
	<div class="ui-state-highlight ui-corner-all" style="margin-bottom: 10px ; display: none">
		<p>
			<span class="ui-icon ui-icon-info"></span>
			<?php echo getNotification() -> message; ?>
		</p>
	</div>

	<?php } ?>
</div>