<?php
require ('/views/SharedManageMenu.php');
?>
<section class="column doubleSized">
	<header>
		<h1>Overview</h1>
		<span id="actions" class="hidden">
			<button>Apply</button>
			<button>Revert</button>
		</span>
	</header>
	
	<h2>Members (<?php echo count($viewBag['members'])?>)</h2>
		<?php foreach($viewBag['members'] as $item) { ?>
			<div class="memberInfo">
				<p title="<?php echo $item -> role ?>"><?php echo $item -> userId ?> 
					 <?php require(ROOT . DS . 'views' . DS . 'SharedMemberTypeDropdown.php');?></p>
			</div>
		<?php } ?>
</section>
<script src="/scripts/manageMembers.js"></script>