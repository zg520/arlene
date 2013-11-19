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
			<div class="nav">
				<ul>
					<li>
						<a href="/">Home</a>
					</li>
					<li>
						<a href="#" id="user-login" class=""><span class=""></span>Login</a>
						<div id="dialog-form" style="width: 100px; height: 250px" title="Login">
							<form id="login-form" action="/members/login" method="POST">
								<fieldset>
									<label for="name" style="display: block;">user name</label>
									<input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" style="display: block;"/>
									<label for="password"style="display: block;">password</label>
									<input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all" style="display: block;"/>
								</fieldset>
							</form>
						</div>
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