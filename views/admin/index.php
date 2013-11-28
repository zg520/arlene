<?php
require ('/views/SharedManageMenu.php');
?>
<section class="column doubleSized">
	<h1>Overview</h1>
	<h2>Awaiting changes - <?php echo count($viewBag['awaitingChanges'])?></h2>
		<?php foreach($viewBag['awaitingChanges'] as $item) { ?>
		<article class="article-box">
			<h1><?php echo $item -> title; ?></h1>
			<div>
				<a href="/edit/article/<?php echo $item -> id ?>">Preview</a>
			</div>
		</article>
		<?php } ?>
	<h2>Under Review - <?php echo count($viewBag['underReview'])?></h2>
		<?php foreach($viewBag['underReview'] as $item) { ?>
		<article class="article-box">
			<h1><?php echo $item -> title; ?></h1>
			<div>
				<a href="/edit/article/<?php echo $item -> id ?>">Preview</a> 
			</div>
		</article>
		<?php } ?>
	<h2>Submitted - <?php echo count($viewBag['submitted'])?></h2>
		<?php foreach($viewBag['submitted'] as $item) { ?>
		<article class="article-box">
			<h1><?php echo $item -> title; ?></h1>
			<div>
				<a href="/edit/article/<?php echo $item -> id ?>">Preview</a>
			</div>
		</article>
		<?php } ?>
		<h2>Published - <?php echo count($viewBag['published'])?></h2>
		<?php foreach($viewBag['published'] as $item) { ?>
		<article class="article-box">
			<h1><?php echo $item -> title; ?></h1>
			<div>
				<a href="/edit/article/<?php echo $item -> id ?>">Preview</a>
			</div>
		</article>
		<?php } ?>
</section>

