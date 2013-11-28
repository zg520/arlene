<aside class="column side-menu">
	<ul>
		<?php if(CurrentUser::hasPublisherAccess()):
		?>
		<h2>Manage Users</h2>
		<li>
			Elevate User Permissions
		</li>
		<?php endif; ?>
		<?php if(CurrentUser::hasEditorAccess()):
		?>
		<h2>Manage Content</h2>
		<li>
			<a href="/edit/articles"><span></span>Edit Articles</a>
		</li>
		<li>
			<a href="/edit/columns"><span></span>Edit Columns</a>
		</li>
		<li>
			<a href="/edit/reviews"><span></span>Edit Reviews</a>
		</li>
		<?php endif; ?>
		<?php if(CurrentUser::hasWriterAccess()):
		?>
		<script src="/scripts/addNewContentForm.js"></script>
		<?php require('SharedaddNewContentForm.php'); ?>
		<h2>Create Content</h2>
		<li>
			<a href="#" id="add-article"><span></span>Add Article</a>
		</li>
		<li>
			<a href="#" id="add-column"><span></span>Add Column</a>
		</li>
		<li>
			<a href="#" id="add-review"><span></span>Add Review</a>
		</li>
		<?php endif; ?>
	</ul>
</aside>
