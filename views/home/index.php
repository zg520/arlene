<div id="wrapper-top">
	<div id="wrapper-top-left">
		<h1>Editor's pick</h1>
			<ul><?php foreach($viewBag['recommended'] as $item) { ?>
				<li><b><?php echo $item -> title; ?></b><br /><?php echo $item -> getSummary(); ?> <a href="/articles/getbyid/<?php echo $item -> id ?>">Read more.</a></li>
					<?php } ?>
			</ul>
	</div>
	<div id="wrapper-top-right">
		<h1>Popular</h1>
			<ul><?php foreach($viewBag['popular'] as $item) { ?>
				<li><b><?php echo $item -> title; ?></b><br /><?php echo $item -> getSummary(); ?><a href="/articles/getbyid/<?php echo $item -> id ?>">Read more.</a></li>
					<?php } ?>
			</ul>
	</div>
	</div>
<div id="wrapper-bottom">
	<h1>Fresh stuff</h1>
	<ul><?php foreach($viewBag['newest'] as $item) { ?>
			<li><b><?php echo $item -> title; ?></b><br /><?php echo $item -> getSummary(); ?><a href="/articles/getbyid/<?php echo $item -> id ?>">Read more.</a></li>
				<?php } ?>
		</ul>
</div>