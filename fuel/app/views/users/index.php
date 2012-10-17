<?php echo render('regions/_header'); ?>

<div id="content-wrapper" class="container_12" role="main">
	<div id="main_content">
	<h2 class="grid_12">List of users</h2>
		<div class="clean"></div>
	<table class='table' id='grid'>
		<thead>
			<th>Username</th><th>E-Mail</th><th>Department</th><th>Type</th><th>Created</th><th>Last Connexion</th>
			<th>Actions</th>
		</thead>
		<tbody>

			<?php foreach($users as $user): ?>
				<?php $meta = Sentry::user(intval($user['id'])); ?>
				<?php $group = $meta->groups() ?>
				<tr>
					<?php if(Sentry::user()->has_access('users_view')): ?>
					<td><?php echo Html::anchor('users/view/'.$user['id'], $user['username']); ?></td>
					<?php else: ?>
					<td><?php echo $user['username']; ?></td>
					<?php endif; ?>
					<td><?php echo $user['email']; ?></td>
					<td><?php echo $meta->get('metadata.department')  ?></td>
					<td><?php echo $group[0]['name'] ?></td>
					<td>
						<abbr title='<?php echo !empty($user['created_at']) ? Date::forge($user['created_at'])->format("%A %d %B %Y") : ""; ?>'>
						<?php echo !empty($user['created_at']) ? Date::time_ago($user['created_at']) : "Never"; ?>
						</abbr>
					</td>
					<td>
						<abbr title='<?php echo !empty($user['last_login']) ? Date::forge($user['last_login'])->format("%A %d %B %Y") : ""; ?>'>
						<?php echo !empty($user['last_login']) ? Date::time_ago($user['last_login']) : "Never"; ?>
						</abbr>
					</td>
					<td> 
					<?php 
					if(Sentry::user(null, true)->has_access('users_edit') || $user['id'] == $current_user['id'])
						echo Html::anchor('users/edit/'.$user['id'], 'Edit');

					if(Sentry::user(null, true)->has_access('users_delete') || $user['id'] == $current_user['id']){
						if(Sentry::user(null, true)->has_access('users_edit') || $user['id'] == $current_user['id'])
							echo $separator;
						echo Html::anchor('users/delete/'.$user['id'], 'Delete', array('onclick' => "return confirm('Are you sure?')"));
					}
						 
					 ?>
					</td>
					
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	</div>
</div>


