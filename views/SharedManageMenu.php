<aside class="column side-menu">
	<ul>
		<?php if(CurrentUser::hasPublisherAccess()):
		?>
		<h2>Manage Users</h2>
		<li>
			<a href="admin/viewmembers"><span></span>Manage Users Permissions</a>
		</li>
		<?php endif; ?>
		<?php if(CurrentUser::hasEditorAccess()):
		?>
		<h2>Manage Content</h2>
		<li>
			<a href="edit/articles"><span></span>Edit Articles</a>
		</li>
		<li>
			<a href="edit/columns"><span></span>Edit Columns</a>
		</li>
		<li>
			<a href="edit/reviews"><span></span>Edit Reviews</a>
		</li>
		<?php endif; ?>
		<?php if(CurrentUser::hasWriterAccess()):
		?>
		<script src="scripts/addNewContentForm.js"></script>
		<?php require('SharedAddNewContentForm.php'); ?>
		<h2>Create Content</h2>
		<li>
			<a href="<?php echo $_GET['path'] ;?>#" id="add-article"><span></span>Add Article</a>
		</li>
		<li>
			<a href="<?php echo $_GET['path'] ;?>#" id="add-column" data-topics = '<?php echo $this -> viewBag['column-topics'] ?>'><span></span>Add Column</a>
		</li>
		<li>
			<a href="<?php echo $_GET['path'] ;?>#" id="add-review" data-topics = '<?php echo $this -> viewBag['review-topics'] ?>'><span></span>Add Review</a>
		</li>
		<?php endif; ?>
	</ul>
</aside>
