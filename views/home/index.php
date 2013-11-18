<div id="wrapper-top">
	<div id="wrapper-top-left">
		<h1>Editor's pick</h1>
			<ul><?php foreach($model['recommended'] as $item) { ?>
				<li><?php echo $item -> title; ?></li>
					<?php } ?>
			</ul>
	</div>
	<div id="wrapper-top-right">
		<h1>Popular</h1>
			<ul><?php foreach($model['popular'] as $item) { ?>
				<li><?php echo $item -> title; ?></li>
					<?php } ?>
			</ul>
	</div>
	</div>
<div id="wrapper-bottom">
	<h1>Fresh stuff</h1>
		<ul><?php foreach($model['newest'] as $item) { ?>
			<li><?php echo $item -> title; ?></li>
				<?php } ?>
		</ul>
</div>