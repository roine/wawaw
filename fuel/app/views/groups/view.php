
<?php echo render('regions/_header'); ?>
<div id="content-wrapper" class="container_12" role="main">
	<div id="main_content">
		<h2 class="grid_12"><?php echo $group['name']; ?></h2>
		<div class="clean"></div>
		<?php foreach($users as $user): ?>
			<?php print_r($user); ?>
			<?php echo Html::anchor('users/view/'.$user['id'], $user['username']); ?>
		<?php endforeach; ?>
			</div>
</div>
