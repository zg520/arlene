<section class="column">
	<h1>Manage</h1>
	<ul>
		<li>Add Article</li>
		<li>Add Column</li>
		<li>Add Review</li>
		<li>Add Article</li>
	</ul>
</section>
<section class="column doubleSized">
	<h1>Overview</h1>
		<?php foreach($viewBag['pendingArticles'] as $item) { ?>
		<article class="article-box">
			<h1><?php echo $item -> title; ?></h1>
			<div>
				<p><?php echo $item -> getSummary(); ?> </p>
				<a href="/articles/getbyid/<?php echo $item -> id ?>">Read more.</a>
			</div>
		</article>
		<?php } ?>
</section>

