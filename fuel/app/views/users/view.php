<?php echo render('regions/_header'); ?>
<div id="content-wrapper" class="container_12" role="main">
	<div id="main_content">
	<h2 class="grid_12"><?php echo ucwords($user->username); ?>'s Profile</h2>
		<div class="clean"></div>
		Groups:
		<?php foreach($user->groups() as $group): ?>
		<?php echo $group['name']; ?>
	<?php endforeach; ?>

	<?php 
		if($current_user->id == $user->id || Sentry::user()->has_access('users_update'))
			echo Html::anchor('users/edit/'.$user->id, 'Edit');
	 ?>
	</div>
</div>
