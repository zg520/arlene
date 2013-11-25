<script src="/scripts/manageHelper.js"></script>
<aside class="column">
	<h1>Manage</h1>
	<ul>
		<?php if(CurrentUser::hasPublisherAccess()): ?>
			
		<?php endif; ?>
		<?php if(CurrentUser::hasEditorAccess()): ?>
			
		<?php endif; ?>
		<?php if(CurrentUser::hasWriterAccess()): ?>
		<li>
			<a href="#" id="add-article"><span></span>Add Article</a>
			<div id="dialog-form" title="New Article">
				<form id="add-article-form" action="/admin/addArticle" method="POST">
					<fieldset style="height: 600px">
						<label for="title" style="display: block;" autofocus>title</label>
						<input type="text" name="title" id="title" class="text ui-widget-content ui-corner-all" style="display: block;"/>
						
						<label for="contents"style="display: block;">contents</label>
						<textarea rows="1" name="contents" id="contents" cols="26" name="reply" class="text ui-widget-content ui-corner-all"></textarea>
						
						<label for="imgUrl" style="display: block;">Image Url</label>
						<input type="text" name="imgUrl" id="imgUrl" class="text ui-widget-content ui-corner-all" style="display: block;"/>
					</fieldset>
				</form>
			</div>
		</li>
		<li>Add Column</li>
		<li>Add Review</li>
		<li>Add Article</li>
		<?php endif; ?>
	</ul>
</aside>
