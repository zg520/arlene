<p>
	<b><div style="height: 30px; font-size: 25px;"><?php echo $viewBag['article'] -> title; ?></div>
	</b><br />
	<img src="<?php echo $viewBag['article'] -> coverUrl?>" alt="Cover Image">
	<div style="display: block;"><?php echo $viewBag['article'] -> body; ?></div>
	<div style="float: right;">Writers: <?php echo $viewBag['article'] -> writers; ?></div>
</p>