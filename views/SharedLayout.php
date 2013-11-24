<!DOCTYPE html>
<html>
	<head>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
			<link href="/css/style.css" rel="stylesheet" type="text/css">
			<link href="/css/jquery-ui-1.10.3.custom.css" rel="stylesheet">
			<script src="/scripts/jquery-1.9.1.js"></script>
			<script src="/scripts/jquery-ui-1.10.3.custom.js"></script>
			<script src="/scripts/common.js"></script>
			<title>Welcome to Zmag. Your daily news digest.</title>
		</head>
	</head>
	<body>
		<div id="header">
			<?php
			require ('/views/SharedHeader.php');
			?>
		</div>
			<?php
			require ('/views/SharedNotifications.php');
			?>
		<div id="wrapper">
			<?php
			require ($this -> viewFile);
			?>
		</div>
		<div id="footer" >
			Copyright &#169 2013 University Of York ghoul IAPT designer. All rights reserved.
		</div>
	</body>
</html>