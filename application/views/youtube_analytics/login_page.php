<div class="row">
	<br/><br/>
	<?php if(isset($limit_cross)) : ?>
		<h4><div class="alert alert-danger text-center"><?php echo $limit_cross; ?></div></h4>
	<?php else : ?>
		<div class="text-center"><?php echo $login_button; ?></div>
	<?php endif; ?>
</div>