<section>
	<h1>Articles</h1>
	<section class="column">
		<h1>Editor's pick</h1>
			<?php foreach($this -> viewBag['recommended'] as $item) { ?>
			<article class="article-box imageOverlay">
				<header>
					<h1><?php echo $item -> title; ?></h1>
					<img class="previewImage" src="<?php echo $item -> coverUrl?>" alt="Cover Image" title="<?php echo $item -> title; ?>">
				</header>
				<div>
					<p><?php echo $item -> getSummary(); ?> </p>
					<a href="read/content/<?php echo $item -> id ?>">Read more.</a>
				</div>
			</article>
			<?php } ?>
	</section>
	<section class="column">
		<h1>Popular</h1>
			<?php foreach($this -> viewBag['popular'] as $item) { ?>
			<article class="article-box imageOverlay">
				<header>
					<h1><?php echo $item -> title; ?></h1>
					<img class="previewImage" src="<?php echo $item -> coverUrl?>" alt="Cover Image" title="<?php echo $item -> title; ?>">
				</header>
				<div>
					<p><?php echo $item -> getSummary(); ?> </p>
					<a href="read/content/<?php echo $item -> id ?>">Read more.</a>
				</div>
			</article>
			<?php } ?>
	</section>
	<section class="column">
		<h1>Hot Now</h1>
			<?php foreach($this -> viewBag['newest'] as $item) { ?>
			<article class="article-box imageOverlay">
				<header>
					<h1><?php echo $item -> title; ?></h1>
					<img class="previewImage" src="<?php echo $item -> coverUrl?>" alt="Cover Image" title="<?php echo $item -> title; ?>">
				</header>
				<div>
					<p><?php echo $item -> getSummary(); ?> </p>
					<a href="read/content/<?php echo $item -> id ?>">Read more.</a>
				</div>
			</article>
			<?php } ?>
	</section>
</section>
</section>