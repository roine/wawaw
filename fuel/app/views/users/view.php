
Groups:
<?php foreach($user->groups() as $group): ?>
	<?php echo $group['name']; ?>
<?php endforeach; ?>

<?php 
if($current_user->id == $user->id || Sentry::user()->has_access('users_update'))
	echo Html::anchor('users/edit/'.$user->id, 'Edit');
?>

<?php 
if($current_user->id != $user->id && Sentry::user()->has_access('messages_send'))
	echo Html::anchor('message/to/'.$user->id, 'send Message');
?>
