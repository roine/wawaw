
<?php foreach($users as $user): ?>
	<?php print_r($user); ?>
	<?php echo Html::anchor('users/view/'.$user['id'], $user['username']); ?>
<?php endforeach; ?>

