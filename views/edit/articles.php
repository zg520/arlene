<?php
require (ROOT. DS . 'views' . DS . 'SharedManageMenu.php');
?>
<section class="column doubleSized">
	<h1>Overview</h1>
	<h2>Under Review - <?php echo count($this -> viewBag['underReview'])?></h2>
		<?php foreach($this -> viewBag['underReview'] as $item) { ?>
		<article class="article-box">
			<h1><?php echo $item -> title; ?></h1>
			<div>
				<a href="edit/content/<?php echo $item -> id ?>">Preview</a> 
				<a href="edit/content/<?php echo $item -> id ?>">Edit</a> 
			</div>
		</article>
		<?php } ?>
	<h2>Submitted - <?php echo count($this -> viewBag['submitted'])?></h2>
		<?php foreach($this -> viewBag['submitted'] as $item) { ?>
		<article class="article-box">
			<h1><?php echo $item -> title; ?></h1>
			<div>
				<a href="edit/content/<?php echo $item -> id ?>"><span class="ui-icon ui-icon-pencil"></span>Preview</a>
			</div>
		</article>
		<?php } ?>
		<h2>Awaiting changes - <?php echo count($this -> viewBag['awaitingChanges'])?></h2>
		<?php foreach($this -> viewBag['awaitingChanges'] as $item) { ?>
		<article class="article-box">
			<h1><?php echo $item -> title; ?></h1>
			<div>
				<a href="edit/content/<?php echo $item -> id ?>">Preview</a>
			</div>
		</article>
		<?php } ?>
</section>

