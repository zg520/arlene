<section class="column">
	<h1>Editor's pick</h1>
		<?php foreach($viewBag['recommended'] as $item) { ?>
		<article class="article-box">
			<header style="position: relative; height: 230px;">
				<h1 style="position: absolute; max-width: 300px;" ><?php echo $item -> title; ?></h1>
				<img class="previewImage" style="position: absolute;" src="<?php echo $item -> coverUrl?>" alt="Cover Image" title="<?php echo $item -> title; ?>">
			</header>
			<div style="position: relative; vertical-align: bottom;">
				<p><?php echo $item -> getSummary(); ?> </p>
				<a href="/articles/getbyid/<?php echo $item -> id ?>">Read more.</a>
			</div>
		</article>
		<?php } ?>
</section>
<section class="column">
	<h1>Popular</h1>
		<?php foreach($viewBag['popular'] as $item) { ?>
		<article class="article-box">
			<header style="position: relative; height: 230px;">
				<h1 style="position: absolute; max-width: 300px;" ><?php echo $item -> title; ?></h1>
				<img class="previewImage" style="position: absolute;" src="<?php echo $item -> coverUrl?>" alt="Cover Image" title="<?php echo $item -> title; ?>">
			</header>
			<div style="position: relative; vertical-align: bottom;">
				<p><?php echo $item -> getSummary(); ?> </p>
				<a href="/articles/getbyid/<?php echo $item -> id ?>">Read more.</a>
			</div>
		</article>
		<?php } ?>
</section>
<section class="column">
	<h1>Hot Now</h1>
		<?php foreach($viewBag['newest'] as $item) { ?>
		<article class="article-box">
			<header style="position: relative; height: 230px;">
				<h1 style="position: absolute; max-width: 300px;" ><?php echo $item -> title; ?></h1>
				<img class="previewImage" style="position: absolute;" src="<?php echo $item -> coverUrl?>" alt="Cover Image" title="<?php echo $item -> title; ?>">
			</header>
			<div style="position: relative; vertical-align: bottom;">
				<p><?php echo $item -> getSummary(); ?> </p>
				<a href="/articles/getbyid/<?php echo $item -> id ?>">Read more.</a>
			</div>
		</article>
		<?php } ?>
</section>