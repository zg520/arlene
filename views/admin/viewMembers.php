<?php
require ('/views/SharedManageMenu.php');
?>
<script src="/scripts/manageMembers.js"></script>
<section class="column doubleSized">
	<header>
		<h1>Overview</h1>
		<span id="actions" class="hidden">
			<button id="apply">Apply</button>
			<button id="revert">Revert</button>
		</span>
	</header>
	<h2>Members (<?php echo count($this->viewBag['members'])?>)</h2>
	<form id="members" action="/admin/editMembers" method="POST">
		<?php foreach($this->viewBag['members'] as $item) { ?>
			<div class="memberInfo">
					<input type="text" name="ids[]" class="userId hidden" value="<?php echo $item -> userId ?>" disabled="disabled" readonly/>
					<input type="text" name="roles[]" class="userRole hidden" value="<?php echo $item -> role ?>" disabled="disabled" readonly/>
				<p><?php echo $item -> userId ?><?php require(ROOT . DS . 'views' . DS . 'SharedMemberTypeDropdown.php');?></p>
			</div>
		<?php } ?>
	</form>
</section>