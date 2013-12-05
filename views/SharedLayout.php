<!DOCTYPE html>
<html>
	<head>
		<head>
			<base href="http://www-student.cs.york.ac.uk/~Y6187553/iapt/001/" target="_blank">
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
			<link href="css/style.css" rel="stylesheet" type="text/css">
			<link href="css/jquery-ui-1.10.3.custom.css" rel="stylesheet">
			<script src="scripts/jquery-1.9.1.js"></script>
			<script src="scripts/jquery-ui-1.10.3.custom.js"></script>
			<script src="scripts/common.js"></script>
			<title>Welcome to Zmag. Your daily news digest.</title>
		</head>
	</head>
	<body>
		<header id="header">
			<?php
			require (ROOT . DS .'views' . DS . 'SharedHeader.php');
			?>
		</header>
			<?php
			require (ROOT . DS .'views' . DS . 'SharedNotifications.php');
			?>
		<main id="wrapper">
			<?php
			require ($this -> viewFile);
			?>
		</main>
		<footer id="footer">Copyright &#169 2013 University Of York Y6187553 IAPT designer. All rights reserved.</footer>
	</body>
</html>