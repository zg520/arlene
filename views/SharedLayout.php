<!DOCTYPE html>
<html>
	<head>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
			<title>Welcome to Zmag. Your daily news digest.</title>
			<link href="css/style.css" rel="stylesheet" type="text/css">
		</head>
	</head>
	<body>
		<div id="header">
			<div class="nav">
				<ul>
					<li>
						<a href="/">Home</a>
					</li>
					<li>
						<a href="#">Login</a>
					</li>
				</ul>
			</div>
		</div>
		<div id="wrapper">
			<?php
				require ($this -> viewFile);
			?>
		</div>
		<div id="footer">
			Copyright &#169 2013 University Of York ghoul IAPT designer. All rights reserved.
		</div>
	</body>
</html>