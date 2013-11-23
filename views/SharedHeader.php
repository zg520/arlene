<div class="nav">
	<ul>
		<li>
			<a href="/">Home</a>
		</li>
		<?php if(currentUser() == null): ?>
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
			<?php else: ?>
		<li>
		<a href="/admin"><span class=""></span>My Articles</a>
		</li>
		<a href="/members/logout" ><span class=""></span>Logout</a>
		</li>
		<?php endif; ?>
	</ul>
</div>