<div class="nav">
	<ul>
		<?php if(CurrentUser::getUser()->isAuthenticated()):?>
		<li><span style="float:left"> Hello	<span id="userInfo" data-role="<?php echo CurrentUser::getUser()->role; ?>"><?php echo CurrentUser::getUser()->userId; ?></span>
		</span></li>
		<?php endif; ?>
		
		<li>
			<a href="/">Home</a>
		</li>
		<li>
			<a href="/read/articlesByDate">Articles</a>
		</li>
		<li>
			<a href="/read/columnsByDate">Columns</a>
		</li>
		<li>
			<a href="/read/reviewsByDate">Reviews</a>
		</li>
		<?php if(!CurrentUser::getUser()->isAuthenticated()):?>
		<li>
			<a href="#" id="user-login" class="">Login</a>
			<div id="dialog-form" title="Login">
				<form id="login-form" action="/members/login" method="POST">
					<fieldset>
						<label for="name" >user name</label>
						<input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all"/>
						<label for="password">password</label>
						<input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all"/>
					</fieldset>
				</form>
			</div>
			<?php else: ?>
		<li>
			<a href="/admin">My Content</a>
		</li>
		<li>
		<a href="/members/logout">Logout</a>
		</li>
		<?php endif; ?>
	</ul>
</div>